@extends('layouts.default')

@section('title', 'AHM Form Capex List')

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
    <li class="breadcrumb-item active" aria-current="page"><span>Capex Request List</span></li>
</ol>
</nav>
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
    <div class="jotform-table-embed" style="width: 100%; height: 600px;" data-id="222701851860049" data-type="interactive"></div>
</div>
</div>
@endsection

@section('scripts')
<script>(function(doc, id){var scripts=doc.getElementsByTagName("script")[0]; if (!doc.getElementById(id)){var script=doc.createElement("script"); script.async=1; script.id=id; script.src="https://cdn.jotfor.ms/common/tableEmbed.js"; scripts.parentNode.insertBefore(script, scripts);}})(document, "jotform-async");</script>
@endsection
