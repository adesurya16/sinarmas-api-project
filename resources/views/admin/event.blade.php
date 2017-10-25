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
    font-size: 14px;
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
  .material-icons{
    display: inline-flex;
    vertical-align: top;
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
              <h4 class="light-green" style="margin-left: 120px; margin-top:13px;">Event Management</h4>
            </div>
          </nav>
        </div>
      </div>
    </div>
    <div class="row" style="margin-top: 25px;">
      <div class="col s12 m10 offset-m2">
        @if($filing_status !== 2)
        <div class="row">
          <div class="col m1" style="font-size: 13px;">
            Today
          </div>
          <div class="col m11">
            <hr>
          </div>
        </div>
        @else
        <div class="row">
          <div class="col m12">
            <hr>
          </div>
        </div>
        @endif
        @foreach ($events_today as $event)
        <div class="col s12 m3">
          <div class="card waves-effect waves" onclick="openModal('{{$event->id}}', '{{$event->event_name}}', '{{$event->event_date}}', '{{$event->event_time}}', '{{$event->location}}', '{{$event->desc}}')" style="width: 100%;">
            <div class="card-content light-green white-text" style="padding: 12px;">
              <p style="font-size:16px;">{{$event->event_name}}</p>
            </div>
            <div class="card-stacked">
              <div class="card-content" style="padding:8px 12px;">
                <p >{{$event->created_by}}</p>
                <p style="font-size: 11px;">{{$event->location}}</p>
              </div>
              <div class="card-action" style="padding:6px 0px; text-align:right;">
                <a href="#" style="font-size: 11px;  margin-right:12px;" class="light-green-text">Event Details</a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  <!-- </div> -->
  @if($filing_status !== 2)
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
      @foreach ($events_previous_day as $event)
      <div class="col s12 m3">
        <div class="card waves-effect waves" onclick="openModal('{{$event->id}}', '{{$event->event_name}}', '{{$event->event_date}}', '{{$event->event_time}}', '{{$event->location}}', '{{$event->desc}}')" style="width: 100%;">
          <div class="card-content light-green white-text" style="padding: 12px;">
            <p style="font-size:16px;">{{$event->event_name}}</p>
          </div>
          <div class="card-stacked">
            <div class="card-content" style="padding:8px 12px;">
              <p >{{$event->created_by}}</p>
              <p style="font-size: 11px;">{{$event->location}}</p>
            </div>
            <div class="card-action" style="padding:6px 0px; text-align:right;">
              <a href="#" style="font-size: 11px;  margin-right:12px;" class="light-green-text">Event Details</a>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif
  <!-- Modal -->
  <!-- Modal Trigger -->
 <!-- Modal Structure -->
 <div id="modal1" class="modal modal-fixed-footer" style="border-radius: 4px 4px 4px 4px; !important;">
  <div class="modal-content" style="border-radius: 4px 4px 0px 0px; !important;">
    <div class="row light-green" style="border-radius: 4px 4px 0px 0px;">
      <div class="col m12 s12 l12" style="border-radius: 2px;">
        <center><h5>Event Details</h5></center>
      </div>
    </div>
    <div class="row">
      <div class="col m12 s12">
        <p class="p-no-margin light-green-text header-text" style="margin-top:0px !important;">Nama Event:</p>
        <p class="p-no-margin grey-text text-darken-2" style="font-size: 17px;" id="event_name_modal">Festival Menanam Pohon</p>
        <p class="p-no-margin header-text"><i class="material-icons light-green-text" style="margin-right: 10px;">event</i><span style="top:2px;" id="event_date_modal">27 Agustus 2017</span><span style="top:2px;"> at </span><span style="top:2px;" id="event_time_modal"></span></p>
        <p class="p-no-margin header-text"><i class="material-icons light-green-text" style="margin-right: 10px;">location_on</i><span style="top:2px;" id="location_modal">Garut, Jawa Barat</span></p>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col m12 s12">
        <p style="font-size:13px;" id="desc_modal">Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku contoh huruf.</p>
      </div>
    </div>
    <!-- <hr>
    <div class="row">
      <div class="col m12 s12">
        <p class="p-no-margin light-green-text header-text" style="margin-top:0px !important;">Sponsor:</p>
      </div>
      <div class="col m4 s6">
        <img class="responsive-img" src="{{asset('assets/images/sponsor/kompas.jpg')}}"/>
      </div>
      <div class="col m4 s6">
        <img class="responsive-img" src="{{asset('assets/images/sponsor/sinarmas.png')}}"/>
      </div>
    </div> -->
  </div>
  @if($filing_status == 0)
  <div class="modal-footer">
    <button class="modal-action modal-close waves-effect waves-green btn light-green" style="font-weight: 300 !important;" onclick="submitForm('1')">Accept</button>
    <button class="modal-action modal-close waves-effect waves-green btn light-green" style="font-weight: 300 !important;" onclick="submitForm('2')">Reject</button>
  </div>
  @endif
 </div>
 <form id="form_submit" action="{{url('/event')}}" method="POST">
   {{csrf_field()}}
   <input type="hidden" value="" name="id_event" id="id_event">
   <input type="hidden" value="" name="filing_status" id="filing_status">
 </form>
 </main>
@endsection

@section('script')
<script type="text/javascript">
var id_profile;
$(document).ready(function(){
  $('.modal').modal();
});

function openModal(id, event_name, event_date, event_time, location, desc) {
  var id_profile = id;
  $('#event_name_modal').html(event_name);
  $('#event_date_modal').html(event_date);
  $('#event_time_modal').html(event_time);
  $('#location_modal').html(location);
  $('#desc_modal').html(desc);

  $('#id_event').val(id);
  $('#modal1').modal('open');
}

function submitForm(filing_status) {
  $('#filing_status').val(filing_status);

  $('#form_submit').submit();
}
</script>

@endsection
