jQuery(document).ready(function($){
    var meta_image_frame;
    $('#upload_image_btn').click(function(e){
        e.preventDefault();
        if ( meta_image_frame ) {
            meta_image_frame.open();
            return;
        }
        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: meta_image.title,
            button: { text:  meta_image.button },
            library: { type: 'image' }
        });
        meta_image_frame.on('select', function(){
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
            $('#foto_kategori').val(media_attachment.url);
        });
        meta_image_frame.open();
    });

	$('.upload_gallery_button').click(function(event){
		var current_gallery = $( this ).closest( 'label' );
		if ( event.currentTarget.id === 'clear-gallery' ) {
			//remove value from input
			current_gallery.find( '.gallery_values' ).val( '' ).trigger( 'change' ); 
			//remove preview images
			current_gallery.find( '.gallery-screenshot' ).html( '' );
			return;
		} 
		// Make sure the media gallery API exists
		if ( typeof wp === 'undefined' || !wp.media || !wp.media.gallery ) {
			return;
		}
		event.preventDefault();
		// Activate the media editor
		var val = current_gallery.find( '.gallery_values' ).val();
		var final; 
		if ( !val ) {
			final = '[gallery ids="0"]';
		} else {
			final = '[gallery ids="' + val + '"]';
		}
		var frame = wp.media.gallery.edit( final );
		frame.state( 'gallery-edit' ).on(
			'update', function( selection ) { 
				//clear screenshot div so we can append new selected images
				current_gallery.find( '.gallery-screenshot' ).html( '' );
				var element, preview_html = '', preview_img;
				var ids = selection.models.map(
					function( e ) {
						element = e.toJSON();
						preview_img = typeof element.sizes.thumbnail !== 'undefined' ? element.sizes.thumbnail.url : element.url;
						preview_html = "<div class='screen-thumb'><img src='" + preview_img + "'/></div>";
						current_gallery.find( '.gallery-screenshot' ).append( preview_html );
						return e.id;
					}
				); 
				current_gallery.find( '.gallery_values' ).val( ids.join( ',' ) ).trigger( 'change' );
			}
		);
		return false;
	});
});