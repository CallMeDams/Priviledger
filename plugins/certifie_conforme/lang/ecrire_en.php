<?php
// SECURITY
if (!defined('_ECRIRE_INC_VERSION')) return;
// This is a SPIP language file
if(_request('exec') == 'article_edit') {
	$GLOBALS[$GLOBALS['idx_lang']] = array(
		'texte_descriptif_rapide' => '<span style="color:#f00"><strong>[SEO] DESCRIPTION</strong></span>'
	);
}
?>