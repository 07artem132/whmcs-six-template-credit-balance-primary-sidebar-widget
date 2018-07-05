<?php

use WHMCS\View\Menu\Item as MenuItem;
use Illuminate\Database\Capsule\Manager as Capsule;

add_hook( 'ClientAreaPrimarySidebar', 1, function ( MenuItem $primarySidebar ) {

	$client = Menu::context( "client" );

	if ( $_SERVER['SCRIPT_NAME'] !== '/clientarea.php' || intval( $client->id ) === 0 ) {
		return;
	}

	$primarySidebar->addChild( 'Client-Balance', array(
		'label' => "Ваш баланс",
		'uri'   => '#',
		'order' => '1',
		'icon'  => 'fa-money'
	) );

	$getCurrency = Capsule::table( 'tblcurrencies' )->where( 'id', $client->currency )->get();

	$balancePanel = $primarySidebar->getChild( 'Client-Balance' );

	$balancePanel->moveToBack();
	$balancePanel->setOrder( 0 );

	$balancePanel->addChild( 'balance-amount', array(
		'uri'   => 'clientarea.php?action=addfunds',
		'label' => '<h4 style="text-align:center;">' . $getCurrency['0']->prefix . $client->credit . ' ' . $getCurrency['0']->suffix . '</h4>',
		'order' => 1
	) );

	$balancePanel->setFooterHtml(
		'<a href="clientarea.php?action=addfunds" class="btn btn-success btn-sm btn-block">
            <i class="fa fa-plus"></i> Пополнить баланс
        </a>'
	);

} );