<?php

namespace App\Http\Controllers\WebService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;
use SimpleXMLElement;
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;
use SAPNWRFC\FunctionCallException as SAPFunctionException;

class BIExchangeRate extends Controller
{
    function simplexml_tree(SimpleXMLElement $sxml, $include_string_content=false, $return=false)
    {
        $indent = "\t";
        $content_extract_size = 15;

        // Get all the namespaces declared at the *root* of this document
        // All the items we're looking at are in the same document, so we only need do this once
        $doc_ns = $sxml->getDocNamespaces(false);
        
        $dump = '';
        // Note that the header is added at the end, so we can add stats

        // The initial object passed in may be a single node or a list of nodes, so we need an outer loop first
        // Note that for a single node, foreach($node) acts like foreach($node->children())
        // Numeric array indexes, however, operate consistently: $node[0] just returns the node
        $root_item_index = 0;
        while ( isset($sxml[$root_item_index]) )
        {
            $root_item = $sxml[$root_item_index];
            
            // The DOM exposes information which SimpleXML doesn't make easy to find
            // Note that this is not an expensive conversion, as we are only swapping PHP wrappers around an existing LibXML resource
            $dom_item = dom_import_simplexml($root_item);

            // To what namespace does this element or attribute belong?
            $ns_prefix = $dom_item->prefix;

            // Special case if the root is actually an attribute
            // It's surprisingly hard to find something which behaves consistently differently for an attribute and an element within SimpleXML
            if ( $dom_item instanceOf DOMAttr )
            {
                if ( $ns_prefix )
                {
                    $dump .= $ns_prefix . ':';
                }
                $dump .=  $root_item->getName() . '="' . (string)$root_item . '"' . PHP_EOL;
            }
            else
            {
                // Display the root node as a numeric key reference, plus a hint as to its tag name 
                // e.g. '[42] // <Answer>'

                if ( $ns_prefix )
                {
                    $root_node_name = $ns_prefix . ':' . $root_item->getName();
                }
                else
                {
                    $root_node_name = $root_item->getName();
                }
                $dump .=  "[$root_item_index] // <$root_node_name>" . PHP_EOL;
                
                // This function is effectively recursing depth-first through the tree,
                // but this is managed manually using a stack rather than actual recursion
                // Each item on the stack is of the form array(int $depth, SimpleXMLElement $element, string $header_row)
                $dump .= $this->simplexml_tree_recursively_process_node(
                    $root_item, 1,
                    $include_string_content, $indent, $content_extract_size
                );
            }
            
            $root_item_index++;
        }

        // Add on the header line, with the total number of items output
        $dump = 'SimpleXML object (' . $root_item_index . ' item' . ($root_item_index > 1 ? 's' : '') . ')' . PHP_EOL . $dump;

        if ( $return )
        {
            return $dump;
        }
        else
        {
            echo $dump;
        }
    }

    /**
     * "Private" function to perform the recursive part of simplexml_tree()
     * Do not call this function directly or rely on its function signature remaining stable
     */
    function simplexml_tree_recursively_process_node($item, $depth, $include_string_content, $indent, $content_extract_size)
    {
        $dump = '';
        
        if ( $include_string_content )
        {
            // Show a chunk of the beginning of the content string, collapsing whitespace HTML-style
            $string_content = (string)$item;
            
            $string_extract = preg_replace('/\s+/', ' ', trim($string_content));
            if ( strlen($string_extract) > $content_extract_size )
            {
                $string_extract = substr($string_extract, 0, $content_extract_size)
                    . '...';
            }
            
            if ( strlen($string_content) > 0 )
            {
                $dump .= str_repeat($indent, $depth)
                    . '(string) '
                    . "'$string_extract'"
                    . ' (' . strlen($string_content) . ' chars)'
                     . PHP_EOL;
            }
        }

        // The DOM exposes information which SimpleXML doesn't make easy to find
        // Note that this is not an expensive conversion, as we are only swapping PHP wrappers around an existing LibXML resource
        $dom_item = dom_import_simplexml($item);

        // To what namespace does this element or attribute belong?
        // For top-level elements, cheat, and say they're in the null namespace, to force a ->children() call
        if ( $depth == 1 )
        {
            $item_ns_prefix = '';
            $item_ns_uri = null;
        }
        else
        {
            $item_ns_prefix = $dom_item->prefix;
            $item_ns_uri = $dom_item->namespaceURI;
        }

        // This returns all namespaces used by this node and all its descendants,
        //  whether declared in this node, in its ancestors, or in its descendants
        $all_ns = $item->getNamespaces(true);
        // If the default namespace is never declared, it will never show up using the below code
        if ( ! array_key_exists('', $all_ns) )
        {
            $all_ns[''] = NULL;
        }

        // Prioritise "current" namespace by merging into onto the beginning of the list
        // (it will be added to the beginning and the duplicate entry dropped)  
        $all_ns = array_merge(
            array($item_ns_prefix => $item_ns_uri),
            $all_ns
        );

        foreach ( $all_ns as $ns_alias => $ns_uri )
        {
            $children = $item->children($ns_alias, true);
            $attributes = $item->attributes($ns_alias, true);
            
            // If things are in the current namespace, display them a bit differently
            $is_current_namespace = ( $ns_uri == $item_ns_uri );

            $ns_uri_quoted = (strlen($ns_uri) == 0 ? 'null' : "'$ns_uri'");

            if ( count($attributes) > 0 )
            {
                if ( ! $is_current_namespace )
                {
                    $dump .= str_repeat($indent, $depth)
                        . "->attributes($ns_uri_quoted)" . PHP_EOL;
                }
                
                foreach ( $attributes as $sx_attribute )
                {
                    // Output the attribute
                    if ( $is_current_namespace )
                    {
                        // In current namespace
                        // e.g. ['attribName']
                        $dump .= str_repeat($indent, $depth)
                            . "['" . $sx_attribute->getName() . "']"
                            . PHP_EOL;
                        $string_display_depth = $depth+1;
                    }
                    else
                    {
                        // After a call to ->attributes()
                        // e.g. ->attribName
                        $dump .= str_repeat($indent, $depth+1)
                            . '->' . $sx_attribute->getName()
                            . PHP_EOL;
                        $string_display_depth = $depth+2;
                    }
                    
                    if ( $include_string_content )
                    {
                        // Show a chunk of the beginning of the content string, collapsing whitespace HTML-style
                        $string_content = (string)$sx_attribute;
                        
                        $string_extract = preg_replace('/\s+/', ' ', trim($string_content));
                        if ( strlen($string_extract) > $content_extract_size )
                        {
                            $string_extract = substr($string_extract, 0, $content_extract_size)
                                . '...';
                        }
                        
                        $dump .= str_repeat($indent, $string_display_depth)
                            . '(string) '
                            . "'$string_extract'"
                            . ' (' . strlen($string_content) . ' chars)'
                             . PHP_EOL;
                    }
                }
            }
            
            if ( count($children) > 0 )
            {
                if ( $is_current_namespace )
                {
                    $display_depth = $depth;
                }
                else
                {
                    $dump .= str_repeat($indent, $depth)
                        . "->children($ns_uri_quoted)" . PHP_EOL;
                    $display_depth = $depth + 1;
                }
                
                // Recurse through the children with headers showing how to access them
                $child_names = array();
                foreach ( $children as $sx_child )
                {
                    // Below is a rather clunky way of saying $child_names[ $sx_child->getName() ]++;
                    //  which avoids Notices about unset array keys
                    $child_node_name = $sx_child->getName();
                    if ( array_key_exists($child_node_name, $child_names) )
                    {
                        $child_names[$child_node_name]++;
                    }
                    else
                    {
                        $child_names[$child_node_name] = 1;
                    }
                    
                    // e.g. ->Foo[0]
                    $dump .= str_repeat($indent, $display_depth)
                        . '->' . $sx_child->getName()
                        . '[' . ($child_names[$child_node_name]-1) . ']'
                        . PHP_EOL;
                    
                    $dump .= $this->simplexml_tree_recursively_process_node(
                        $sx_child, $display_depth+1,
                        $include_string_content, $indent, $content_extract_size
                    );
                }
            }
        }
        
        return $dump;
    }

    function simplexml_dump(SimpleXMLElement $sxml, $return=false)
    {
        $indent = "\t";

        // Get all the namespaces declared at the *root* of this document
        // All the items we're looking at are in the same document, so we only need do this once
        $doc_ns = $sxml->getDocNamespaces(false);
        
        $dump = '';
        // Note that the header is added at the end, so we can add stats
        $dump .= '[' . PHP_EOL;

        // SimpleXML objects can be either a single node, or (more commonly) a list of 0 or more nodes
        // I haven't found a reliable way of distinguishing between the two cases
        // Note that for a single node, foreach($node) acts like foreach($node->children())
        // Numeric array indexes, however, operate consistently: $node[0] just returns the node
        $item_index = 0;
        while ( isset($sxml[$item_index]) )
        {
            $item = $sxml[$item_index];
            $item_index++;

            // It's surprisingly hard to find something which behaves consistently differently for an attribute and an element within SimpleXML
            // The below relies on the fact that the DOM makes a much clearer distinction
            // Note that this is not an expensive conversion, as we are only swapping PHP wrappers around an existing LibXML resource
            $dom_item = dom_import_simplexml($item);

            // To what namespace does this element or attribute belong? Returns array( alias => URI )
            $ns_prefix = $dom_item->prefix;
            $ns_uri = $dom_item->namespaceURI;

            if ( $dom_item instanceOf DOMAttr )
            {
                $dump .= $indent . 'Attribute {' . PHP_EOL;

                if ( ! is_null($ns_uri) )
                {
                    $dump .= $indent . $indent . 'Namespace: \'' . $ns_uri . '\'' . PHP_EOL;
                    if ( $ns_prefix == '' )
                    {
                        $dump .= $indent . $indent . '(Default Namespace)' . PHP_EOL;
                    }
                    else
                    {
                        $dump .= $indent . $indent . 'Namespace Alias: \'' . $ns_prefix . '\'' . PHP_EOL;
                    }
                }

                $dump .= $indent . $indent . 'Name: \'' . $item->getName() . '\'' . PHP_EOL;
                $dump .= $indent . $indent . 'Value: \'' . (string)$item . '\'' . PHP_EOL;

                $dump .= $indent . '}' . PHP_EOL;
            }
            else
            {
                $dump .= $indent . 'Element {' . PHP_EOL;

                if ( ! is_null($ns_uri) )
                {
                    $dump .= $indent . $indent . 'Namespace: \'' . $ns_uri . '\'' . PHP_EOL;
                    if ( $ns_prefix == '' )
                    {
                        $dump .= $indent . $indent . '(Default Namespace)' . PHP_EOL;
                    }
                    else
                    {
                        $dump .= $indent . $indent . 'Namespace Alias: \'' . $ns_prefix . '\'' . PHP_EOL;
                    }
                }

                $dump .= $indent . $indent . 'Name: \'' . $item->getName() . '\'' . PHP_EOL;
                // REMEMBER: ALWAYS CAST TO STRING! :)
                $dump .= $indent . $indent . 'String Content: \'' . (string)$item . '\'' . PHP_EOL;

                // Now some statistics about attributes and children, by namespace

                // This returns all namespaces used by this node and all its descendants,
                //  whether declared in this node, in its ancestors, or in its descendants
                $all_ns = $item->getNamespaces(true);
                // If the default namespace is never declared, it will never show up using the below code
                if ( ! array_key_exists('', $all_ns) )
                {
                    $all_ns[''] = NULL;
                }
                
                foreach ( $all_ns as $ns_alias => $ns_uri )
                {
                    $children = $item->children($ns_uri);
                    $attributes = $item->attributes($ns_uri);
                    
                    // Somewhat confusingly, in the case where a parent element is missing the xmlns declaration,
                    //  but a descendant adds it, SimpleXML will look ahead and fill $all_ns[''] incorrectly
                    if ( 
                        $ns_alias == ''
                        &&
                        ! is_null($ns_uri)
                        &&
                        count($children) == 0
                        &&
                        count($attributes) == 0
                    )
                    {
                        // Try looking for a default namespace without a known URI
                        $ns_uri = NULL;
                        $children = $item->children($ns_uri);
                        $attributes = $item->attributes($ns_uri);
                    }

                    // Don't show zero-counts, as they're not that useful
                    if ( count($children) == 0 && count($attributes) == 0 )
                    {
                        continue;
                    }

                    $ns_label = (($ns_alias == '') ? 'Default Namespace' : "Namespace $ns_alias");
                    $dump .= $indent . $indent . 'Content in ' . $ns_label . PHP_EOL;

                    if ( ! is_null($ns_uri) )
                    {
                        $dump .= $indent . $indent . $indent . 'Namespace URI: \'' . $ns_uri . '\'' . PHP_EOL;
                    }
                    
                    // Count occurrence of child element names, rather than listing them all out
                    $child_names = array();
                    foreach ( $children as $sx_child )
                    {
                        // Below is a rather clunky way of saying $child_names[ $sx_child->getName() ]++;
                        //  which avoids Notices about unset array keys
                        $child_node_name = $sx_child->getName();
                                            if ( array_key_exists($child_node_name, $child_names) )
                                            {
                                                    $child_names[$child_node_name]++;
                                            }
                                            else
                                            {
                                                    $child_names[$child_node_name] = 1;
                                            }
                    }
                    ksort($child_names);
                    $child_name_output = array();
                    foreach ( $child_names as $name => $count )
                    {
                        $child_name_output[] = "$count '$name'";
                    }
                    
                    $dump .= $indent . $indent . $indent . 'Children: ' . count($children);
                    // Don't output a trailing " - " if there are no children
                    if ( count($children) > 0 )
                    {
                        $dump .= ' - ' . implode(', ', $child_name_output);
                    }
                    $dump .= PHP_EOL;
                    
                    // Attributes can't be duplicated, but I'm going to put them in alphabetical order
                    $attribute_names = array();
                    foreach ( $attributes as $sx_attribute )
                    {
                        $attribute_names[] = "'" . $sx_attribute->getName() . "'";
                    }
                    ksort($attribute_names);
                    $dump .= $indent . $indent . $indent . 'Attributes: ' . count($attributes);
                    // Don't output a trailing " - " if there are no attributes
                    if ( count($attributes) > 0 )
                    {
                        $dump .= ' - ' . implode(', ', $attribute_names);
                    }
                    $dump .= PHP_EOL;
                }

                $dump .= $indent . '}' . PHP_EOL;
            }
        }
        $dump .= ']' . PHP_EOL;

        // Add on the header line, with the total number of items output
        $dump = 'SimpleXML object (' . $item_index . ' item' . ($item_index > 1 ? 's' : '') . ')' . PHP_EOL . $dump;

        if ( $return )
        {
            return $dump;
        }
        else
        {
            echo $dump;
        }
    }

    public function index(Request $request, $rate="USD"){
        try {
            // define('NS_XML', 'urn:schemas-microsoft-com:xml-diffgram-v1');
            // $param = array(
            //     'currency'=>$rate,
            //     'date'=>date('Y-m-d')
            // );
            // $url = config('intranet.BI_EXCHANGE')."?mts=".$param['currency']."&startdate=".$param['date']."&enddate=".$param['date'];
            // // $url = config('intranet.BI_EXCHANGE');
            // $client = new \GuzzleHttp\Client();
            // $header = ['headers' => []];
            // $res = $client->get($url, $header);
            // $content = $res->getBody()->getContents();
            // $xml = simplexml_load_string($content);
            // // To see all hidden children in XML
            // // $this->simplexml_tree($xml);
            // $harga_jual = 0;
            // $harga_beli = 0;
            // $harga_median = 0;
            // try {
            //     $harga_beli = (float)$xml->children(NS_XML)->diffgram[0]->children(null)->NewDataSet[0]->Table[0]->beli_subkurslokal[0];
            // } catch(\Exception $e){}
            // try {
            //     $harga_jual = (float)$xml->children(NS_XML)->diffgram[0]->children(null)->NewDataSet[0]->Table[0]->jual_subkurslokal[0];
            // } catch(\Exception $e){}
            // try {
            //     $harga_median = (float)($harga_jual + $harga_beli) / 2;
            // } catch(\Exception $e){}
            $is_production = config('intranet.is_production');
            $exc_rate = config('intranet.EXCHANGE_RATE');
            $data_insert = [];
            if(is_array($exc_rate) && count($exc_rate) > 0){
                if($is_production){
                    $rfc = new SapConnection(config('intranet.rfc_prod'));
                }else{
                    $rfc = new SapConnection(config('intranet.rfc'));
                }
                $options = [
                    'rtrim'=>true,
                ];

                $date_exchange = date('Ymd');
                for($i=0;$i<count($exc_rate);$i++){
                    $param = array(
                        'P_RATE_TYPE'=>'M',
                        'P_FROM_CURRENCY'=>$exc_rate[$i],
                        'P_TO_CURRENCY'=>'IDR',
                        'P_DATE'=>$date_exchange
                    );
                    $function_type = $rfc->getFunction('ZFM_MD_EXCHANGE_RATE');
                    $exchange_rate= $function_type->invoke($param, $options);
                    if(isset($exchange_rate['IT_EXCHANGE_RATE'][0]['RATE'])){
                        $harga_median = $exchange_rate['IT_EXCHANGE_RATE'][0]['RATE'];
                        $data_insert[] = [
                            'BUSINESS_DATE' => date('Y-m-d', strtotime($date_exchange)),
                            'KURS_JUAL' => 0,
                            'KURS_BELI' => 0,
                            'KURS_TENGAH' => $harga_median,
                            'CURRENCY' => $exc_rate[$i]
                        ];
                    }
                }
                if(count($data_insert) > 0){
                    DB::connection('dbayana-stg')->beginTransaction();

                    try {
                        DB::connection('dbayana-stg')
                        ->table('BIExchangeRate')
                        ->where('BUSINESS_DATE', date('Y-m-d', strtotime($date_exchange)))
                        ->delete();

                        DB::connection('dbayana-stg')
                        ->table('BIExchangeRate')
                        ->insert($data_insert);

                        DB::connection('dbayana-stg')->commit();
                        
                        // all good
                    } catch (\Exception $e) {
                        DB::connection('dbayana-stg')->rollback();
                        return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
                        // something went wrong
                    }
                    return response()->json(['status'=>'success', 'message'=>'Success insert data exchange', 'data_exchange'=>$data_insert], 200);
                }
                else
                    return response()->json(['status'=>'warning', 'message'=>'No data available', 'data_to_insert'=>$data_insert], 200);
                
            } else
                throw new \Exception("No Exchange Rate configuration found, please add them first and make sure the currency code is available");
                

        } catch(\Exception $e){
            return response()->json(['status'=>'error', 'message'=>$e->getMessage(), 'detailed_error'=>$e], 400);
        }
    }
}
