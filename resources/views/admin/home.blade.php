@extends('master')

@section('css')
<style>
  .modal {
    width: 25% !important ;
    height: 80% !important ;
    max-height: 100% !important

  }
  .modal-content{
    padding-top: 0px !important;
    padding-left: 0px !important;
    padding-right: 0px !important;
  }
  .p-no-margin {
    margin: 0px;
    font-size: 12px;
  }
  .header-text {
    margin-top: 10px;
    font-size: 11px;
  }
  h5{
    color: white;
    margin: 25px 40px;
    font-weight: lighter;
  }
  hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #ddd;
    margin: 1em 0;
    padding: 0;
  }
  .active-toolbar{
    background-color: #8bc34a;
  }

</style>
@endsection
@section('content')
  @include('admin.header')
  <!-- <div class="container"> -->
  <main>
    <div class="row light-green white-text">
      <div class="col s12 m11 offset-m1">
        <div class="navbar-fixed">
          <nav class="light-green">
            <div class="nav-wrapper">
              <h4 class="light-green" style="margin-left: 120px; margin-top:13px;"></h4>
            </div>
          </nav>
        </div>
      </div>
    </div>
 </main>
@endsection
