<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'navbarMiddleware'], function () {

    Auth::routes();
    /*
        Route::get('/', function () {
            return view('welcome');
        });
    */
    Route::get('/', 'HomeController@index')->name('home');
    //Route::get('/', 'HomeController@indexMKM')->name('home');

    Route::get('/home', 'HomeController@index')->name('home');

    //shopping routes
    Route::get('/shopping/singles/{edition_id}', ['as' => 'shopping.singles', 'uses' => 'Shopping\SinglesController@showList']);
    Route::get('/shopping/item/{itemId}', ['as' => 'shopping.show', 'uses' => 'Shopping\ShowController@showItem']);
    Route::get('/shopping/search', ['as' => 'shopping.search', 'uses' => 'Shopping\SinglesController@search']);
    Route::get('/shopping/searchCard', ['as' => 'shopping.searchCard', 'uses' => 'Shopping\SinglesController@searchCard']);
    Route::get('/shopping/{category}', ['as' => 'shopping.category', 'uses' => 'Shopping\CategoryController@showCategory']);

    //cart routes
    Route::get('/cart', ['as' => 'cart.show', 'uses' => 'Cart\indexController@showIndex']);
    Route::post('/cart/add', ['as' => 'cart.add', 'uses' => 'Cart\indexController@addItem']);
    Route::post('/cart/remove', ['as' => 'cart.remove', 'uses' => 'Cart\indexController@removeItem']);
    Route::post('/cart/buy', ['as' => 'cart.buy', 'uses' => 'Cart\indexController@buy']);
    Route::post('/cart/confirm', ['as' => 'cart.confirm', 'uses' => 'Cart\indexController@confirm']);

    //command routes
    Route::get('/command', ['as' => 'command.index', 'uses' => 'Command\IndexController@index']);
    Route::get('/command/{command_id}', ['as' => 'command.show', 'uses' => 'Command\IndexController@showIndex']);
    Route::get('/command/printable/{command_id}', ['as' => 'command.showPrintable', 'uses' => 'Command\IndexController@showPrintable']);
    Route::get('/command/{command_id}/{state_id}', ['as' => 'command.changeState', 'uses' => 'Command\IndexController@changeState']);

    //payment routes
    Route::get('/payment/{payment_id}', ['as' => 'payment.show', 'uses' => 'Payment\IndexController@show']);

    //user routes
    Route::get('/profile', ['as' => 'user.profileGet', 'uses' => 'User\IndexController@profileGet']);
    Route::post('/profile', ['as' => 'user.profilePost', 'uses' => 'User\IndexController@profilePost']);
    Route::POST('/user/saveAddress', ['as' => 'user.saveAddress', 'uses' => 'User\IndexController@saveAddress']);
    Route::POST('/user/addAddress', ['as' => 'user.addAddress', 'uses' => 'User\IndexController@addAddress']);

    //admin routes
    Route::get('/admin', ['as' => 'admin.index', 'uses' => 'Admin\IndexController@index']);

    //adding cards to stock
    Route::get('/admin/addCardSelect', ['as' => 'admin.addCardSelect', 'uses' => 'Admin\AddCardController@addCardSelect']);
    Route::get('/admin/addCardView/{edition_id}', ['as' => 'admin.addCardViewGet', 'uses' => 'Admin\AddCardController@addCardViewGet']);
    Route::post('/admin/addCardView/{edition_id}', ['as' => 'admin.addCardViewPost', 'uses' => 'Admin\AddCardController@addCardViewPost']);

    //editing card list
    Route::get('/admin/editCardsSelect', ['as' => 'admin.editCardsSelect', 'uses' => 'Admin\EditCardController@editCardsSelect']);
    Route::get('/admin/editCardsView/{edition_id}', ['as' => 'admin.editCardsViewGet', 'uses' => 'Admin\EditCardController@editCardsViewGet']);
    Route::post('/admin/editCardsView/{edition_id}', ['as' => 'admin.editCardsViewPost', 'uses' => 'Admin\EditCardController@editCardsViewPost']);

    //adding new booster
    Route::get('/admin/addBoosterSelect', ['as' => 'admin.addBoosterSelect', 'uses' => 'Admin\AddBoosterController@addBoosterSelect']);
    Route::get('/admin/addBoosterView/{id}', ['as' => 'admin.addBoosterViewGet', 'uses' => 'Admin\AddBoosterController@addBoosterViewGet']);
    Route::post('/admin/addBoosterView/{id}', ['as' => 'admin.addBoosterViewPost', 'uses' => 'Admin\AddBoosterController@addBoosterViewPost']);

    //adding new booster box
    Route::get('/admin/addBoosterBoxSelect', ['as' => 'admin.addBoosterBoxSelect', 'uses' => 'Admin\AddBoosterBoxController@addBoosterBoxSelect']);
    Route::get('/admin/addBoosterBoxView/{id}', ['as' => 'admin.addBoosterBoxViewGet', 'uses' => 'Admin\AddBoosterBoxController@addBoosterBoxViewGet']);
    Route::post('/admin/addBoosterBoxView/{id}', ['as' => 'admin.addBoosterBoxViewPost', 'uses' => 'Admin\AddBoosterBoxController@addBoosterBoxViewPost']);

    //adding new product
    Route::get('/admin/addNewProduct', ['as' => 'admin.addNewProductGet', 'uses' => 'Admin\AddNewProductController@addNewProductGet']);
    Route::post('/admin/addNewProduct', ['as' => 'admin.addNewProductPost', 'uses' => 'Admin\AddNewProductController@addNewProductPost']);

    //CSV import export
    Route::get('/admin/importFromCsv', ['as' => 'admin.importFromCsvGet', 'uses' => 'Admin\CsvController@importFromCsvGet']);
    Route::post('/admin/importFromCsv', ['as' => 'admin.importFromCsvPost', 'uses' => 'Admin\CsvController@importFromCsvPost']);

    Route::get('/admin/deleteByCsv', ['as' => 'admin.deleteByCsvGet', 'uses' => 'Admin\CsvController@deleteByCsvGet']);
    Route::post('/admin/deleteByCsv', ['as' => 'admin.deleteByCsvPost', 'uses' => 'Admin\CsvController@deleteByCsvPost']);

    Route::get('/admin/completeIdsFromCsv', ['as' => 'admin.completeIdsFromCsvGet', 'uses' => 'Admin\CsvController@completeIdsFromCsvGet']);
    Route::post('/admin/completeIdsFromCsv', ['as' => 'admin.completeIdsFromCsvPost', 'uses' => 'Admin\CsvController@completeIdsFromCsvPost']);
    Route::post('/admin/completeIdsFromCsv2', ['as' => 'admin.completeIdsFromCsvPost2', 'uses' => 'Admin\CsvController@completeIdsFromCsvPost']);



    //import and export stock
    Route::get('/admin/importStock', ['as' => 'admin.importStock', 'uses' => 'Admin\CsvController@importStock']);
    Route::get('/admin/exportStock', ['as' => 'admin.exportStock', 'uses' => 'Admin\CsvController@exportStock']);

    //edition list route
    Route::get('/admin/EditionList', ['as' => 'admin.getEditionList', 'uses' => 'Admin\AddEditionController@getEditionList']);
    //adding new edition
    Route::get('/admin/addEdition', ['as' => 'admin.addEditionGet', 'uses' => 'Admin\AddEditionController@addEditionGet']);
    Route::post('/admin/addEdition', ['as' => 'admin.addEditionPost', 'uses' => 'Admin\AddEditionController@addEditionPost']);
    Route::post('/admin/addCard', ['as' => 'admin.addCardPost', 'uses' => 'Admin\AddEditionController@addCardPost']);


    //get buy list route
    Route::get('/getBuyList', ['as' => 'admin.getBuyList', 'uses' => 'Admin\BuyListController@getBuyList']);
    Route::get('/getBuyListByEdition', ['as' => 'admin.getBuyListByEditionGet', 'uses' => 'Admin\BuyListController@getBuyListByEditionGet']);
    Route::post('/getBuyListByEdition', ['as' => 'admin.getBuyListByEditionPost', 'uses' => 'Admin\BuyListController@getBuyListByEditionPost']);

    //commands routes
    Route::get('/admin/commands', ['as' => 'admin.commands', 'uses' => 'Admin\CommandController@commands']);
    Route::post('/commands/removeItem', ['as' => 'command.removeItem', 'uses' => 'Command\IndexController@removeItem']);
    Route::post('/commands/addItem', ['as' => 'command.addItem', 'uses' => 'Command\IndexController@addItem']);

    //MKM api routes
    Route::get('/admin/connect', ['as' => 'admin.connect', 'uses' => 'Admin\MKMController@connect']);
    Route::get('/admin/setEditionIds', ['as' => 'admin.setEditionIds', 'uses' => 'Admin\MKMController@setMKMExpansionIds']);
    Route::get('/admin/setProductsIds', ['as' => 'admin.setProductsIds', 'uses' => 'Admin\MKMController@setMKMProductsIds']);
    Route::post('/admin/setMKMProductId', ['as' => 'admin.setMKMProductId', 'uses' => 'Admin\MKMController@setMKMProductId']);
    Route::get('/admin/getMKMSingles', ['as' => 'admin.getMKMSingles', 'uses' => 'Admin\MKMController@getMKMSingles']);

    //adding and removing single card from common card list
    Route::post('/admin/addCardSingle', ['as' => 'admin.addCardSinglePost', 'uses' => 'Admin\AddCardController@addCardSinglePost']);
    Route::post('/admin/removeCardSingle', ['as' => 'admin.removeCardSinglePost', 'uses' => 'Admin\AddCardController@removeCardSinglePost']);

    //editing edition
    Route::get('/admin/Edition', ['as' => 'admin.EditionCheckGet', 'uses' => 'Admin\EditionController@editionCheckGet']);
    Route::post('/admin/Edition', ['as' => 'admin.EditionCheckPost', 'uses' => 'Admin\EditionController@editionCheckPost']);

    //remove edition
    Route::get('/admin/EditionRemove', ['as' => 'admin.EditionRemoveGet', 'uses' => 'Admin\EditionController@editionRemoveGet']);
    Route::post('/admin/EditionRemove', ['as' => 'admin.EditionRemovePost', 'uses' => 'Admin\EditionController@editionRemovePost']);

    //edition statistics
    Route::get('/admin/editionStatistics', ['as' => 'admin.editionsStatistic', 'uses' => 'Admin\EditionStatisticController@editionsSatatisticGet']);
    Route::post('/admin/editionStatistics', ['as' => 'admin.editionsStatistic', 'uses' => 'Admin\EditionStatisticController@editionsStatisticPost']);



    Route::get('/admin/blbost', ['as' => 'admin.blbost', 'uses' => 'Admin\EditCardController@blbost']);

    Route::get('/admin/MKMAddEdition', ['as' => 'admin.MKMAddEditionSelect', 'uses' => 'Admin\MKMController@addEditionSelect']);
    Route::get('/admin/MKMAddEdition/{id}', ['as' => 'admin.MKMAddEdition', 'uses' => 'Admin\MKMController@addEdition']);
    Route::get('/admin/MKMCheckProductIds/{id}', ['as' => 'admin.MKMCheckProductIds', 'uses' => 'Admin\MKMController@CheckProductIds']);

    Route::get('/admin/checkCardOnMKM/{id}', ['as' => 'admin.checkCardOnMKM', 'uses' => 'Admin\MKMController@checkCard']);
    Route::get('/admin/checkCardOnMKMApi', ['as' => 'admin.checkCardOnMKMApi', 'uses' => 'Admin\MKMController@checkCardApi']);

    Route::get('/admin/stocking', ['as' => 'admin.stockingList', 'uses' => 'Admin\StockingController@stockingList']);
    Route::post('/admin/stocking', ['as' => 'admin.stocking', 'uses' => 'Admin\StockingController@stockingPost']);
    Route::get('/admin/stockingShowGet', ['as' => 'admin.stockingShowGet', 'uses' => 'Admin\StockingController@stockingShowGet']);
    Route::post('/admin/stockingShow', ['as' => 'admin.stockingShow', 'uses' => 'Admin\StockingController@stockingShow']);

});
