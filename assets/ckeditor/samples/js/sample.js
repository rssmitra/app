/**
 * Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

/* exported initSample */

CKEDITOR.config.removeFormatTags = 'b,big,cite,code,del,dfn,em,font,i,ins,kbd,q,s,samp,small,span,strike,strong,sub,sup,tt,u,var';
// CKEDITOR.config.removePlugins = 'elementspath, save, font, blockquote, clipboard,  button, panelbutton, panel, floatpanel, colorbutton, colordialog, div, elementspath, enterkey, entities, popup,  filebrowser, find, fakeobjects, flash, floatingspace, listblock, richcombo, newpage, pagebreak, preview, print, removeformat, resize, save, menubutton, scayt, selectall, showblocks,  showborders, smiley, specialchar, stylescombo, tab, table, format, forms, horizontalrule, htmlwriter, iframe, image, indent,  exportpdf,  templates,  copyformatting';
CKEDITOR.config.removePlugins = 'elementspath, blockquote, clipboard,  button, panelbutton, panel, floatpanel, colorbutton, colordialog, div, elementspath, enterkey, entities, popup,  filebrowser, fakeobjects, flash, floatingspace, listblock, richcombo, newpage, pagebreak, menubutton, scayt, selectall, showblocks,  showborders, smiley, specialchar, stylescombo, tab, forms, iframe, image, indent,  exportpdf';


if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
	CKEDITOR.tools.enableHtml5Elements( document );

// The trick to keep the editor in the sample quite small
// unless user specified own height.
CKEDITOR.config.height = 150;
CKEDITOR.config.width = 'auto';

var initSample = ( function() {
	var wysiwygareaAvailable = isWysiwygareaAvailable(),
		isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );

	return function() {
		var editorElement = CKEDITOR.document.getById( 'editor' );

		// :(((
		if ( isBBCodeBuiltIn ) {
			editorElement.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=https://ckeditor.com]CKEditor[/url].'
			);
		}

		// Depending on the wysiwygarea plugin availability initialize classic or inline editor.
		if ( wysiwygareaAvailable ) {
			CKEDITOR.replace( 'editor' );
		} else {
			editorElement.setAttribute( 'contenteditable', 'true' );
			CKEDITOR.inline( 'editor' );

			// TODO we can consider displaying some info box that
			// without wysiwygarea the classic editor may not work.
		}
	};

	function isWysiwygareaAvailable() {
		// If in development mode, then the wysiwygarea must be available.
		// Split REV into two strings so builder does not replace it :D.
		if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
			return true;
		}

		return !!CKEDITOR.plugins.get( 'wysiwygarea' );
	}
} )();

