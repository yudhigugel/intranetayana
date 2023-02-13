<script type="text/javascript">
	// $(function(){
	// 	try {
	// 		// Clear semua session storage jika tidak sesuai path
	// 		if(Object.keys(sessionStorage).includes('dt_path_from')){
	// 			var parsed_json = JSON.parse(sessionStorage.getItem('dt_path_from'));
	// 			var allowed_routes = (parsed_json.path == location.pathname || parsed_json.allowed == location.href)
	// 			// if(!allowed_routes)
	// 				// sessionStorage.clear();

	// 		}
	// 	} catch(error) { console.log(`Error when trying to clear state ${error}`); }
	// });


    function readNotif(){
        $.ajax({
            url: "{{url('notifications/read-notif')}}",
            type: "POST",
            data : {},
            success : function (response){
                $(".count-circle").hide();
            },
            error : function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            }
        });
    }

    function fnBrowserDetect(){ 
        let userAgent = navigator.userAgent;
        let browserName;
        if(userAgent.match(/chrome|chromium|crios/i)){
             browserName = "chrome";
        }else if(userAgent.match(/firefox|fxios/i)){
             browserName = "firefox";
        }  else if(userAgent.match(/safari/i)){
             browserName = "safari";
        }else if(userAgent.match(/opr\//i)){
             browserName = "opera";
        } else if(userAgent.match(/edg/i)){
             browserName = "edge";
        }else{
             browserName="No browser detection";
        }
        return browserName;         
    }
</script>
