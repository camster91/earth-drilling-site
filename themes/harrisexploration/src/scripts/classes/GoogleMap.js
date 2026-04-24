/* global google */
import $ from 'jquery';

class GoogleMap {
  constructor(el) {
    this.el = $(el);
    this.icon = {
      path: 'M25,0C15.957,0,8.625,7.277,8.625,16.253C8.625,31.638,25,50,25,50s16.375-17.37,16.375-33.747 C41.375,7.277,34.044,0,25,0z M25,23.084c-3.383,0-6.125-2.742-6.125-6.125s2.742-6.125,6.125-6.125s6.125,2.742,6.125,6.125 S28.383,23.084,25,23.084z',
      fillColor: '#ed1c2e',
      fillOpacity: 1,
      anchor: new google.maps.Point(25, 50),
      strokeWeight: 0,
      scale: 1,
    };
    this.animation = google.maps.Animation.DROP;
  }

  centerMap() {
    const bounds = new google.maps.LatLngBounds();
    $.each(this.map.markers, (i, marker) => {
      const latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
      bounds.extend(latlng);
    });
    if (this.map.markers.length === 1) {
      this.map.setCenter(bounds.getCenter());
    } else {
      this.map.fitBounds(bounds);
    }
  }

  getMarker(item) {
    const position = new google.maps.LatLng(item.attr('data-lat'), item.attr('data-lng'));
    return new google.maps.Marker({
      position,
      map: this.map,
      icon: this.icon,
      animation: this.animation,
    });
  }

  render(styles = []) {
    const markers = this.el.find('.marker');
    this.map = new google.maps.Map(this.el[0], {
      styles,
      zoom: 13,
      navigationControl: false,
      mapTypeControl: false,
    });
    this.map.markers = [];
    markers.each((index, item) => {
      const marker = this.getMarker($(item));
      this.map.markers.push(marker);
    });
    this.centerMap();
    this.map.setOptions({ scrollwheel: false });
    google.maps.event.addListener(
      this.map, 'click',
      () => this.map.setOptions({ scrollwheel: true }),
    );
  }
}

export default GoogleMap;
