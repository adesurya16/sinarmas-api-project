@extends('master')
@section('content-body')
  background="{{asset('/asset/background_login.jpeg')}}"
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col m4 offset-m4">
      <div class="card" style="margin-top: 100px; border-radius: 10px !important;">
        <div class="card-content light-green darken-1" style="border-radius: 10px 10px 0px 0px!important;">
          <center><h5 style="color:white; font-weight:300;">MASUK</h5></center>
        </div>
        <div class="card-content white" style="border-radius: 0px 0px 10px 10px!important;">
          <form action="{{url('login')}}" method="POST">
            <div class="row" style="margin-top: 50px;">
              <div class="input-field col m12">
                <input type="password" name="password"/>
                <label for="password">Password Anda</label>
              </div>
            </div>
            <div class="row">
              <div class="col m6 offset-m6">
                <input class="waves-effect waves-light btn light-green darken-1" type="submit" value="Log in"/>
              </div>
            </div>
            {{ csrf_field() }}
          </form>
          <div class="row" style="margin-top: 120px; margin-bottom: 0px;">
            <div class="col m4 offset-m4">
              <center><img class="responsive-img" src="{{asset('/asset/sinarmas_logo.png')}}"/></center>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
