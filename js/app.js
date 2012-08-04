$( "#files a" ).draggable();

$( "#trash" ).droppable({
	drop: function( event, ui ) {
        var url = [location.protocol, '//', location.host, location.pathname].join('');
	    
	    window.location.href = url + '?delete=' + $( ui.draggable ).attr( 'id' );
	}
});

