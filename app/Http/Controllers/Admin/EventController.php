<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Events;
use App\Sponsor;
use App\EventSponsor;

class EventController extends Controller
{
    //

    public function getVerifiedEvent() {
      return $this->getEvent(1);
    }

    public function getPendingEvent() {
      return $this->getEvent(0);
    }

    private function getEvent($filing_status) {
      $events_init_today = Events::where('filing_status', $filing_status)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->get();
      $events_init_previous_day = Events::where('filing_status', $filing_status)->where('created_at', '<', date('Y-m-d').' 00:00:00')->get();

      $events_today = [];
      $events_previous_day = [];

      foreach ($events_init_today as $event) {
        $event_sponsors = EventSponsor::where('event_id', $event->id)->get();
        $sponsors = [];

        foreach ($event_sponsors as $event_sponsor) {
          $sponsor = Sponsor::find($event_sponsor->sponsor_id);
          array_push($sponsors, $sponsor);
        }

        $event->sponsors = $sponsors;
        array_push($events_today, $event);
      }

      foreach ($events_init_previous_day as $event) {
        $event_sponsors = EventSponsor::where('event_id', $event->id)->get();
        $sponsors = [];

        foreach ($event_sponsors as $event_sponsor) {
          $sponsor = Sponsor::find($event_sponsor->sponsor_id);
          array_push($sponsors, $sponsor);
        }

        $event->sponsors = $sponsors;
        array_push($events_previous_day, $event);
      }
      // echo json_encode($events_today);
      // echo json_encode($events_previous_day);
      // die();
      return view('admin.event', ['events_today' => $events_today, 'events_previous_day' => $events_previous_day, 'filing_status' => $filing_status]);
    }

    public function getOnGoingEvent() {
      $events_today = Events::where('filing_status', 1)->where('event_date', date('Y-m-d'))->get();

      return view('admin.event', ['events_today' => $events_today, 'events_previous_day' => [], 'filing_status' => 2]);
    }

    public function changeVerifiedEvent(Request $request) {
      $filing_status = $request->filing_status;
      $id = $request->id_event;

      $event = Events::find($id);
      $event->filing_status = $filing_status;
      $event->save();

      return redirect()->back();

    }
}
