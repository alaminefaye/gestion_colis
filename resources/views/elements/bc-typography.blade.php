@extends('layouts.app')

@section('title', 'Typography - Éléments UI')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Typography</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Éléments UI</a></li>
          <li class="breadcrumb-item" aria-current="page">Typography</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5>Headings</h5>
      </div>
      <div class="card-body">
        <h1>h1. Bootstrap heading</h1>
        <h2>h2. Bootstrap heading</h2>
        <h3>h3. Bootstrap heading</h3>
        <h4>h4. Bootstrap heading</h4>
        <h5>h5. Bootstrap heading</h5>
        <h6>h6. Bootstrap heading</h6>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5>Display Headings</h5>
      </div>
      <div class="card-body">
        <h1 class="display-1">Display 1</h1>
        <h1 class="display-2">Display 2</h1>
        <h1 class="display-3">Display 3</h1>
        <h1 class="display-4">Display 4</h1>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5>Inline Text Elements</h5>
      </div>
      <div class="card-body">
        <p>You can use the mark tag to <mark>highlight</mark> text.</p>
        <p><del>This line of text is meant to be treated as deleted text.</del></p>
        <p><s>This line of text is meant to be treated as no longer accurate.</s></p>
        <p><ins>This line of text is meant to be treated as an addition to the document.</ins></p>
        <p><u>This line of text will render as underlined</u></p>
        <p><small>This line of text is meant to be treated as fine print.</small></p>
        <p><strong>This line rendered as bold text.</strong></p>
        <p><em>This line rendered as italicized text.</em></p>
      </div>
    </div>
  </div>
</div>
@endsection
