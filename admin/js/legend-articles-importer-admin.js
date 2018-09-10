(function( $ ) {

	'use strict';

	$(document).ready( function() {

		$( '#import-articles' ).submit( function( e ) {

			e.preventDefault();
			e.stopImmediatePropagation();

			$( '#import-article button' ).prop( 'disabled', true );

			$.ajax({
				method: 'post',
				url: ajax.ajax_url,
				data: {
					action: 'get_articles'
				},
				success: save_articles,
				error: function( error ) {
					console.log( error );
				}
			});

			return false;

		} );

	} );

	function save_articles( result ) {

		let html = $.parseHTML( result.data.output );

		var elems = $( '.pressBoxes', html );

		var posts = _.map( elems, function( elem ) {

			return {
				'title': $( 'h4', elem ).text(),
				'author': $( 'h2', elem ).text(),
				'link': $( 'a', elem ).attr( 'href' ),
				'image': $( elem ).css( 'background-image' ).replace(/.*\s?url\([\'\"]?/, '').replace(/[\'\"]?\).*/, '')
			}

		} );

		$.ajax({
			method: 'post',
			url: ajax.ajax_url,
			data: {
				action: 'save_articles',
				articles: posts
			},
			success: function( result ) {
				console.log(result);
				$( '#import-articles button' ).prop( 'disabled', false );
			},
			error: function( error ) {
				console.log(result);
				$( '#import-articles button' ).prop( 'disabled', false );
			}
		});

	};

})( jQuery );
