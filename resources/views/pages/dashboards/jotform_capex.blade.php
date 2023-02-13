@extends('layouts.default')

@section('title', 'AHM Form Capex Request')

@section('custom_source_css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/css/vendor/cropme.min.css">
<style>
    .field-cc{
        width: 80%;
        font-size: 14px;
        font-weight: bold;
        border-radius: 3px;
    }

    .field-cc::-webkit-input-placeholder{
        font-weight: normal;
        color:#5b5a5a;
    }
    .field-cc:-ms-input-placeholder{
        font-weight: normal;
        color:#5b5a5a;
    }
    .field-cc::placeholder{
        font-weight: normal;
        color:#5b5a5a;
    }

</style>
@endsection

@section('extra_inline_styles')
@endsection


@section('content')
<nav aria-label="breadcrumb">
<ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="#">AHM Forms</a></li>
    <li class="breadcrumb-item active" aria-current="page"><span>Capex Form</span></li>
</ol>
</nav>
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
    <div class="card" style="background: #ecedf3">
        <div class="card-body">
            <div class="col-8 offset-md-2">
                {{--<h2 class="text-center">Jotform</h2>--}}
                <div class="form-group clearfix">
                    <iframe
                      id="JotFormIFrame-222701851860049"
                      title="CAPEX Request Form"
                      onload="window.parent.scrollTo(0,0)"
                      allowtransparency="true"
                      allowfullscreen="true"
                      allow="geolocation; microphone; camera"
                      src="https://form.jotform.com/222701851860049"
                      frameborder="0"
                      style="
                      min-width: 100%;
                      height:539px;
                      border:none;"
                      scrolling="no"
                    >
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  var ifr = document.getElementById("JotFormIFrame-222701851860049");
  if (ifr) {
    var src = ifr.src;
    var iframeParams = [];
    if (window.location.href && window.location.href.indexOf("?") > -1) {
      iframeParams = iframeParams.concat(window.location.href.substr(window.location.href.indexOf("?") + 1).split('&'));
    }
    if (src && src.indexOf("?") > -1) {
      iframeParams = iframeParams.concat(src.substr(src.indexOf("?") + 1).split("&"));
      src = src.substr(0, src.indexOf("?"))
    }
    iframeParams.push("isIframeEmbed=1");
    ifr.src = src + "?" + iframeParams.join('&');
  }
  window.handleIFrameMessage = function(e) {
    if (typeof e.data === 'object') { return; }
    var args = e.data.split(":");
    if (args.length > 2) { iframe = document.getElementById("JotFormIFrame-" + args[(args.length - 1)]); } else { iframe = document.getElementById("JotFormIFrame"); }
    if (!iframe) { return; }
    switch (args[0]) {
      case "scrollIntoView":
        iframe.scrollIntoView();
        break;
      case "setHeight":
        iframe.style.height = args[1] + "px";
        break;
      case "collapseErrorPage":
        if (iframe.clientHeight > window.innerHeight) {
          iframe.style.height = window.innerHeight + "px";
        }
        break;
      case "reloadPage":
        window.location.reload();
        break;
      case "loadScript":
        if( !window.isPermitted(e.origin, ['jotform.com', 'jotform.pro']) ) { break; }
        var src = args[1];
        if (args.length > 3) {
            src = args[1] + ':' + args[2];
        }
        var script = document.createElement('script');
        script.src = src;
        script.type = 'text/javascript';
        document.body.appendChild(script);
        break;
      case "exitFullscreen":
        if      (window.document.exitFullscreen)        window.document.exitFullscreen();
        else if (window.document.mozCancelFullScreen)   window.document.mozCancelFullScreen();
        else if (window.document.mozCancelFullscreen)   window.document.mozCancelFullScreen();
        else if (window.document.webkitExitFullscreen)  window.document.webkitExitFullscreen();
        else if (window.document.msExitFullscreen)      window.document.msExitFullscreen();
        break;
    }
    var isJotForm = (e.origin.indexOf("jotform") > -1) ? true : false;
    if(isJotForm && "contentWindow" in iframe && "postMessage" in iframe.contentWindow) {
      var urls = {"docurl":encodeURIComponent(document.URL),"referrer":encodeURIComponent(document.referrer)};
      iframe.contentWindow.postMessage(JSON.stringify({"type":"urls","value":urls}), "*");
    }
  };
  window.isPermitted = function(originUrl, whitelisted_domains) {
    var url = document.createElement('a');
    url.href = originUrl;
    var hostname = url.hostname;
    var result = false;
    if( typeof hostname !== 'undefined' ) {
      whitelisted_domains.forEach(function(element) {
          if( hostname.slice((-1 * element.length - 1)) === '.'.concat(element) ||  hostname === element ) {
              result = true;
          }
      });
      return result;
    }
  };
  if (window.addEventListener) {
    window.addEventListener("message", handleIFrameMessage, false);
  } else if (window.attachEvent) {
    window.attachEvent("onmessage", handleIFrameMessage);
  }
</script>
@endsection
