/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2009 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * This file define the HTML entities handled by the editor.
 */

var FCKXHtmlEntities = new Object() ;

FCKXHtmlEntities.Initialize = function()
{
	if ( FCKXHtmlEntities.Entities )
		return ;

	var sChars = '' ;
	var oEntities, e ;

	if ( FCKConfig.ProcessHTMLEntities )
	{
		FCKXHtmlEntities.Entities = {
			// Latin-1 Entities
			'В ':'nbsp',
			'ВЎ':'iexcl',
			'Вў':'cent',
			'ВЈ':'pound',
			'В¤':'curren',
			'ВҐ':'yen',
			'В¦':'brvbar',
			'В§':'sect',
			'ВЁ':'uml',
			'В©':'copy',
			'ВЄ':'ordf',
			'В«':'laquo',
			'В¬':'not',
			'В­':'shy',
			'В®':'reg',
			'ВЇ':'macr',
			'В°':'deg',
			'В±':'plusmn',
			'ВІ':'sup2',
			'Ві':'sup3',
			'Вґ':'acute',
			'Вµ':'micro',
			'В¶':'para',
			'В·':'middot',
			'Вё':'cedil',
			'В№':'sup1',
			'Вє':'ordm',
			'В»':'raquo',
			'Вј':'frac14',
			'ВЅ':'frac12',
			'Вѕ':'frac34',
			'Вї':'iquest',
			'Г—':'times',
			'Г·':'divide',

			// Symbols

			'Ж’':'fnof',
			'вЂў':'bull',
			'вЂ¦':'hellip',
			'вЂІ':'prime',
			'вЂі':'Prime',
			'вЂѕ':'oline',
			'вЃ„':'frasl',
			'в„':'weierp',
			'в„‘':'image',
			'в„њ':'real',
			'в„ў':'trade',
			'в„µ':'alefsym',
			'в†ђ':'larr',
			'в†‘':'uarr',
			'в†’':'rarr',
			'в†“':'darr',
			'в†”':'harr',
			'в†µ':'crarr',
			'в‡ђ':'lArr',
			'в‡‘':'uArr',
			'в‡’':'rArr',
			'в‡“':'dArr',
			'в‡”':'hArr',
			'в€Ђ':'forall',
			'в€‚':'part',
			'в€ѓ':'exist',
			'в€…':'empty',
			'в€‡':'nabla',
			'в€€':'isin',
			'в€‰':'notin',
			'в€‹':'ni',
			'в€Џ':'prod',
			'в€‘':'sum',
			'в€’':'minus',
			'в€—':'lowast',
			'в€љ':'radic',
			'в€ќ':'prop',
			'в€ћ':'infin',
			'в€ ':'ang',
			'в€§':'and',
			'в€Ё':'or',
			'в€©':'cap',
			'в€Є':'cup',
			'в€«':'int',
			'в€ґ':'there4',
			'в€ј':'sim',
			'в‰…':'cong',
			'в‰€':'asymp',
			'в‰ ':'ne',
			'в‰Ў':'equiv',
			'в‰¤':'le',
			'в‰Ґ':'ge',
			'вЉ‚':'sub',
			'вЉѓ':'sup',
			'вЉ„':'nsub',
			'вЉ†':'sube',
			'вЉ‡':'supe',
			'вЉ•':'oplus',
			'вЉ—':'otimes',
			'вЉҐ':'perp',
			'в‹…':'sdot',
			'\u2308':'lceil',
			'\u2309':'rceil',
			'\u230a':'lfloor',
			'\u230b':'rfloor',
			'\u2329':'lang',
			'\u232a':'rang',
			'в—Љ':'loz',
			'в™ ':'spades',
			'в™Ј':'clubs',
			'в™Ґ':'hearts',
			'в™¦':'diams',

			// Other Special Characters

			'"':'quot',
		//	'&':'amp',		// This entity is automatically handled by the XHTML parser.
		//	'<':'lt',		// This entity is automatically handled by the XHTML parser.
			'>':'gt',			// Opera and Safari don't encode it in their implementation
			'Л†':'circ',
			'Лњ':'tilde',
			'вЂ‚':'ensp',
			'вЂѓ':'emsp',
			'вЂ‰':'thinsp',
			'вЂЊ':'zwnj',
			'вЂЌ':'zwj',
			'вЂЋ':'lrm',
			'вЂЏ':'rlm',
			'вЂ“':'ndash',
			'вЂ”':'mdash',
			'вЂ':'lsquo',
			'вЂ™':'rsquo',
			'вЂљ':'sbquo',
			'вЂњ':'ldquo',
			'вЂќ':'rdquo',
			'вЂћ':'bdquo',
			'вЂ ':'dagger',
			'вЂЎ':'Dagger',
			'вЂ°':'permil',
			'вЂ№':'lsaquo',
			'вЂє':'rsaquo',
			'в‚¬':'euro'
		} ;

		// Process Base Entities.
		for ( e in FCKXHtmlEntities.Entities )
			sChars += e ;

		// Include Latin Letters Entities.
		if ( FCKConfig.IncludeLatinEntities )
		{
			oEntities = {
				'ГЂ':'Agrave',
				'ГЃ':'Aacute',
				'Г‚':'Acirc',
				'Гѓ':'Atilde',
				'Г„':'Auml',
				'Г…':'Aring',
				'Г†':'AElig',
				'Г‡':'Ccedil',
				'Г€':'Egrave',
				'Г‰':'Eacute',
				'ГЉ':'Ecirc',
				'Г‹':'Euml',
				'ГЊ':'Igrave',
				'ГЌ':'Iacute',
				'ГЋ':'Icirc',
				'ГЏ':'Iuml',
				'Гђ':'ETH',
				'Г‘':'Ntilde',
				'Г’':'Ograve',
				'Г“':'Oacute',
				'Г”':'Ocirc',
				'Г•':'Otilde',
				'Г–':'Ouml',
				'Г':'Oslash',
				'Г™':'Ugrave',
				'Гљ':'Uacute',
				'Г›':'Ucirc',
				'Гњ':'Uuml',
				'Гќ':'Yacute',
				'Гћ':'THORN',
				'Гџ':'szlig',
				'Г ':'agrave',
				'ГЎ':'aacute',
				'Гў':'acirc',
				'ГЈ':'atilde',
				'Г¤':'auml',
				'ГҐ':'aring',
				'Г¦':'aelig',
				'Г§':'ccedil',
				'ГЁ':'egrave',
				'Г©':'eacute',
				'ГЄ':'ecirc',
				'Г«':'euml',
				'Г¬':'igrave',
				'Г­':'iacute',
				'Г®':'icirc',
				'ГЇ':'iuml',
				'Г°':'eth',
				'Г±':'ntilde',
				'ГІ':'ograve',
				'Гі':'oacute',
				'Гґ':'ocirc',
				'Гµ':'otilde',
				'Г¶':'ouml',
				'Гё':'oslash',
				'Г№':'ugrave',
				'Гє':'uacute',
				'Г»':'ucirc',
				'Гј':'uuml',
				'ГЅ':'yacute',
				'Гѕ':'thorn',
				'Гї':'yuml',
				'Е’':'OElig',
				'Е“':'oelig',
				'Е ':'Scaron',
				'ЕЎ':'scaron',
				'Её':'Yuml'
			} ;

			for ( e in oEntities )
			{
				FCKXHtmlEntities.Entities[ e ] = oEntities[ e ] ;
				sChars += e ;
			}

			oEntities = null ;
		}

		// Include Greek Letters Entities.
		if ( FCKConfig.IncludeGreekEntities )
		{
			oEntities = {
				'О‘':'Alpha',
				'О’':'Beta',
				'О“':'Gamma',
				'О”':'Delta',
				'О•':'Epsilon',
				'О–':'Zeta',
				'О—':'Eta',
				'О':'Theta',
				'О™':'Iota',
				'Ољ':'Kappa',
				'О›':'Lambda',
				'Оњ':'Mu',
				'Оќ':'Nu',
				'Оћ':'Xi',
				'Оџ':'Omicron',
				'О ':'Pi',
				'ОЎ':'Rho',
				'ОЈ':'Sigma',
				'О¤':'Tau',
				'ОҐ':'Upsilon',
				'О¦':'Phi',
				'О§':'Chi',
				'ОЁ':'Psi',
				'О©':'Omega',
				'О±':'alpha',
				'ОІ':'beta',
				'Оі':'gamma',
				'Оґ':'delta',
				'Оµ':'epsilon',
				'О¶':'zeta',
				'О·':'eta',
				'Оё':'theta',
				'О№':'iota',
				'Оє':'kappa',
				'О»':'lambda',
				'Ој':'mu',
				'ОЅ':'nu',
				'Оѕ':'xi',
				'Ої':'omicron',
				'ПЂ':'pi',
				'ПЃ':'rho',
				'П‚':'sigmaf',
				'Пѓ':'sigma',
				'П„':'tau',
				'П…':'upsilon',
				'П†':'phi',
				'П‡':'chi',
				'П€':'psi',
				'П‰':'omega',
				'\u03d1':'thetasym',
				'\u03d2':'upsih',
				'\u03d6':'piv'
			} ;

			for ( e in oEntities )
			{
				FCKXHtmlEntities.Entities[ e ] = oEntities[ e ] ;
				sChars += e ;
			}

			oEntities = null ;
		}
	}
	else
	{
		FCKXHtmlEntities.Entities = {
			'>':'gt' // Opera and Safari don't encode it in their implementation
		} ;
		sChars = '>';

		// Even if we are not processing the entities, we must render the &nbsp;
		// correctly. As we don't want HTML entities, let's use its numeric
		// representation (&#160).
		sChars += 'В ' ;
	}

	// Create the Regex used to find entities in the text.
	var sRegexPattern = '[' + sChars + ']' ;

	if ( FCKConfig.ProcessNumericEntities )
		sRegexPattern = '[^ -~]|' + sRegexPattern ;

	var sAdditional = FCKConfig.AdditionalNumericEntities ;

	if ( sAdditional && sAdditional.length > 0 )
		sRegexPattern += '|' + FCKConfig.AdditionalNumericEntities ;

	FCKXHtmlEntities.EntitiesRegex = new RegExp( sRegexPattern, 'g' ) ;
}
