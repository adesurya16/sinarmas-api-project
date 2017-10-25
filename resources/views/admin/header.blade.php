<header>
  <ul id="nav-mobile" class="side-nav fixed blue-grey lighten-4" style="width: 15%;">
    <li class="logo" style="height: 150px;">
      <a href="{{url('/home')}}" id="logo-container" href="/" class="brand-logo" style="height: 150px;">
        <img class="responsive-img" src="{{asset('/asset/sinarmas_logo.png')}}" style="margin-top: 50px;"/>
        <!-- <object id="front-page-logo" type="image/svg+xml" data="res/materialize.svg">Your browser does not support SVG</object> -->
      </a>
    </li>
    <li class="no-padding">
      <ul class="collapsible collapsible-accordion">
        <li class="bold"><a class="collapsible-header  waves-effect waves-teal"><i class="material-icons">account_box</i>Account</a>
          <div class="collapsible-body blue-grey lighten-4">
            <ul>
              <li><a href="{{url('/user/pending')}}"><i class="material-icons">remove_circle</i>Not Yet Verified</a></li>
              <li><a href="{{url('/user/verify')}}"><i class="material-icons">check_circle</i>Verified</a></li>
            </ul>
          </div>
        </li>
        <li class="bold"><a class="collapsible-header  waves-effect waves-teal"><i class="material-icons">event</i>Event</a>
          <div class="collapsible-body blue-grey lighten-4">
            <ul>
              <!-- <li class="active-toolbar"><a href="#"><i class="material-icons">remove_circle</i>Not Yet Verified</a></li> -->
              <li><a href="{{url('/event/pending')}}"><i class="material-icons">remove_circle</i>Not Yet Verified</a></li>
              <li><a href="{{url('/event/verify')}}"><i class="material-icons">check_circle</i>Verified</a></li>
              <li><a href="{{url('/event/on_going')}}"><i class="material-icons">alarm_on</i>On Going</a></li>
            </ul>
          </div>
        </li>
        <li class="bold"><a class="collapsible-header" href="{{url('/logout')}}"><i class="material-icons">lock</i>Logout</a>
      </ul>
    </li>
  </ul>
</header>


<!-- <li class="bold"><a class="collapsible-header active waves-effect waves-teal">JavaScript</a>
  <div class="collapsible-body">
    <ul>
      <li><a href="carousel.html">Carousel</a></li>
      <li class="active"><a href="collapsible.html">Collapsible</a></li>
      <li><a href="dialogs.html">Dialogs</a></li>
      <li><a href="dropdown.html">Dropdown</a></li>
      <li><a href="feature-discovery.html">FeatureDiscovery</a></li>
      <li><a href="media.html">Media</a></li>
      <li><a href="modals.html">Modals</a></li>
      <li><a href="parallax.html">Parallax</a></li>
      <li><a href="pushpin.html">Pushpin</a></li>
      <li><a href="scrollfire.html">ScrollFire</a></li>
      <li><a href="scrollspy.html">Scrollspy</a></li>
      <li><a href="side-nav.html">SideNav</a></li>
      <li><a href="tabs.html">Tabs</a></li>
      <li><a href="transitions.html">Transitions</a></li>
      <li><a href="waves.html">Waves</a></li>
    </ul>
  </div>
</li> -->
