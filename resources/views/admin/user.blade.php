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
              <h4 class="light-green" style="margin-left: 120px; margin-top:13px;">Account Management</h4>
            </div>
          </nav>
        </div>
      </div>
    </div>
    <!-- <div class="navbar-fixed">
      <nav>
        <div class="nav-wrapper">
          <h2 class="light-green ">User Management</h2>
        </div>
      </nav>
    </div> -->
    <div class="row" style="margin-top: 25px;">
      <div class="col s12 m10 offset-m2">

        <div class="row">
          <div class="col m1" style="font-size: 13px;">
            Today
          </div>
          <div class="col m11">
            <hr>
          </div>
        </div>
        @foreach ($users_today as $user)
        <div class="col s12 m3" onclick="openModal('{{$user->profile->id}}', '{{$user->profile->fullname}}', '{{$user->user->email}}', '{{$user->profile->birthday}}', '{{$user->self_image}}', '{{$user->image_id_card}}', '{{$user->profile->avatar}}')">
          <div class="card horizontal waves-effect waves">
            <div class="card-image">
              <img style="height: 100px; width: 100px;" src="{{asset($user->profile->avatar)}}">
            </div>
            <div class="card-stacked">
              <div class="card-content" style="padding:6px;">
                <p >{{$user->profile->fullname}}</p>
                <p style="font-size: 11px;">{{$user->user->email}}</p>
              </div>
              <div class="card-action" style="padding:6px 0px; text-align:right;">
                <a href="#" style="font-size: 11px;" class="light-green-text">COMPLETE PROFILE</a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  <!-- </div> -->
  <div class="row">
    <div class="col s12 m10 offset-m2">
      <div class="row">
        <div class="col m1" style="font-size: 13px;">
          Previous Day
        </div>
        <div class="col m11">
          <hr>
        </div>
      </div>
      @foreach ($users_previous_day as $user)
      <div class="col s12 m3" onclick="openModal('{{$user->profile->id}}', '{{$user->profile->fullname}}', '{{$user->user->email}}', '{{$user->profile->birthday}}', '{{$user->self_image}}', '{{$user->image_id_card}}', '{{$user->profile->avatar}}')">
        <div class="card horizontal waves-effect waves">
          <div class="card-image">
            <img style="height: 100px; width: 100px;" src="{{asset($user->profile->avatar)}}">
          </div>
          <div class="card-stacked">
            <div class="card-content" style="padding:6px;">
              <p >{{$user->profile->fullname}}</p>
              <p style="font-size: 11px;">{{$user->user->email}}</p>
            </div>
            <div class="card-action" style="padding:6px 0px; text-align:right;">
              <a href="#" style="font-size: 11px;" class="light-green-text">COMPLETE PROFILE</a>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Modal -->
  <!-- Modal Trigger -->
 <!-- Modal Structure -->
 <div id="modal1" class="modal modal-fixed-footer" style="border-radius: 4px 4px 4px 4px; !important;">
  <div class="modal-content" style="border-radius: 4px 4px 0px 0px; !important;">
    <div class="row light-green" style="border-radius: 4px 4px 0px 0px;">
      <div class="col m12 s12 l12" style="border-radius: 2px;">
        <center><h5>Complete Profile</h5></center>
      </div>
    </div>
    <div class="row">
      <div class="col m6 s6">
        <img id="avatar_modal" style="height: auto; width: 100%; border-radius: 4px;" src="{{asset('/asset/background_login.jpeg')}}">
      </div>
      <div class="col m6 s6">
        <p class="p-no-margin light-green-text header-text" style="margin-top:0px !important;">Nama:</p>
        <p id="name_modal" class="p-no-margin grey-text text-darken-2" style="font-size: 13px;"></p>
        <p class="p-no-margin light-green-text header-text">Email:</p>
        <p id="email_modal" class="p-no-margin grey-text"></p>
        <p class="p-no-margin light-green-text header-text">Ulang Tahun:</p>
        <p id="birthday_modal"class="p-no-margin grey-text"></p>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col m12 s12">
        <p class="p-no-margin"><span class="header-text light-green-text">Teman:</span><span class="grey-text">132</span></p>
        <p class="p-no-margin"><span class="header-text light-green-text">Acara yang sudah diikuti:</span><span class="grey-text">12</span></p>
        <p class="p-no-margin"><span class="header-text light-green-text">Foto:</span></p>
        <p class="p-no-margin"><img id="self_image_modal" style="height: 70px; width:70px;"></p>
        <p class="p-no-margin"><span class="header-text light-green-text">Foto KTP:</span></p>
        <p class="p-no-margin"><img id="image_id_card_modal" src="{{asset('/asset/background_login.jpeg')}}" style="height: 100px; width:100%;"></p>
      </div>
    </div>
  </div>
  @if($filing_status == 0)
  <div class="modal-footer">
    <button class="modal-action modal-close waves-effect waves-green btn light-green" style="font-weight: 300 !important;" onclick="submitForm('1')">Accept</button>
    <button class="modal-action modal-close waves-effect waves-green btn light-green" style="font-weight: 300 !important;" onclick="submitForm('2')">Reject</button>
  </div>
  @endif
 </div>
 </main>
 <form id="form_submit" action="{{url('/user')}}" method="POST">
   {{csrf_field()}}
   <input type="hidden" value="" name="id_profile" id="id_profile">
   <input type="hidden" value="" name="filing_status" id="filing_status">
 </form>
@endsection

@section('script')
<script type="text/javascript">
var id_profile;
$(document).ready(function(){
  $('.modal').modal();
});

function openModal(id, fullname, email, birthday, self_image, image_id_card, avatar) {
  var id_profile = id;
  $('#name_modal').html(fullname);
  $('#email_modal').html(email);
  $('#birthday_modal').html(birthday);
  $('#self_image_modal').attr("src","{{url('/')}}"+self_image);
  $('#image_id_card_modal').attr("src","{{url('/')}}"+image_id_card);
  $('#avatar_modal').attr("src","{{url('/')}}"+avatar);
  $('#id_profile').val(id);
  $('#modal1').modal('open');
}

function submitForm(filing_status) {
  $('#filing_status').val(filing_status);

  $('#form_submit').submit();
}
</script>

@endsection
