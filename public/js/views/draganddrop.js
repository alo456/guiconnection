// external js: packery.pkgd.js, draggabilly.pkgd.js

var $grid = $('.grid').packery({
  itemSelector: '.grid-item',
  //gutter: 1,
  columnWidth: 200
});

// make all grid-items draggable
$grid.find('.grid-item').each( function( i, gridItem ) {
  var draggie = new Draggabilly( gridItem );
  // bind drag events to Packery
  $grid.packery( 'bindDraggabillyEvents', draggie );
});
$grid.on( 'dblclick', '.grid-item', function( event ) {
  // change size of item by toggling large class
  $(  event.currentTarget  ).toggleClass('grid-item--width2');
  // trigger layout after item size changes
  $grid.packery('layout');
});

// show item order after layout
function orderItems() {
  var itemElems = $grid.packery('getItemElements');
  $( itemElems ).each( function( i, itemElem ) {
    $(itemElem).text( i + 1 );
  });
}

$grid.on( 'layoutComplete', orderItems );
$grid.on( 'dragItemPositioned', orderItems );
