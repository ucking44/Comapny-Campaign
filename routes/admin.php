<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SpecialDateController;
use App\Http\Controllers\GameCampaignController;
use App\Http\Controllers\BonusCampaignController;
use App\Http\Controllers\BonusPeriodicController;
use App\Http\Controllers\PromoCampaignController;
use App\Http\Controllers\SpendCampaignController;
use App\Http\Controllers\LslTransactionController;
use App\Http\Controllers\DiscountBenefitController;
use App\Http\Controllers\ReferalCampaignController;
use App\Http\Controllers\CashBackCampaignController;
use App\Http\Controllers\CashBackPeriodicController;
use App\Http\Controllers\UpgradePrivilegeController;
use App\Http\Controllers\FixedBudgetCampaignController;
use App\Http\Controllers\ProductTransactionsController;
use App\Http\Controllers\PointAwardTransactionController;
use App\Http\Controllers\FrequencyCountPeriodicController;
use App\Http\Controllers\ManualDebitTransactionController;
use App\Http\Controllers\ManualCreditTransactionController;
use App\Http\Controllers\CumulativePeriodicCampaignController;
use App\Http\Controllers\FixedBudgetPeriodicCampaignController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('generate-token', [ProgramController::class, 'generateToken']);
Route::get('generate-token', [ProgramController::class, 'getToken']);

$router->group(['middleware'=> ['admin-admin', 'generate-token']], function() use ($router)
{
    //////////////////////        PRODUCT TRANSACTION       ///////////////////
    Route::get('loyalty-program', [ProductTransactionsController::class, 'getAllLoyaltyProgram']);
    Route::get('all-branches', [ProductTransactionsController::class, 'getAllBranches']);
    Route::get('all-sweepstake', [ProductTransactionsController::class, 'getSweepstake']);
    Route::get('all-product-group', [ProductTransactionsController::class, 'getAllProductGroup']);
    Route::get('transaction-channel', [ProductTransactionsController::class, 'allItems']);
    Route::post('save-transaction-channel', [ProductTransactionsController::class, 'saveTransactionChannel']);
    Route::post('save-sweepstake', [ProductTransactionsController::class, 'saveSweepstake']);
    Route::post('save-partner-category', [ProductTransactionsController::class, 'savePartnerCategory']);
    Route::post('save-partner', [ProductTransactionsController::class, 'storePartner']);
    Route::post('save-product-group', [ProductTransactionsController::class, 'saveProductGroup']);
    Route::post('save-product-brand', [ProductTransactionsController::class, 'saveProductBrand']);

     //////////////////////        SPEND CAMPAIGN       /////////////////////
    Route::get('spend-campaign', [SpendCampaignController::class, 'getSpendCampaign']);
    Route::post('save-spend-campaign', [SpendCampaignController::class, 'saveSpendCampaign']);
    Route::put('update-spend-campaign/{id}', [SpendCampaignController::class, 'updateSpendCampaign']);
    Route::delete('delete-spend-campaign/{id}', [SpendCampaignController::class, 'deleteSpendCampaign']);

    //////////////////////        FIXED BUDGET CAMPAIGN       /////////////////////
    Route::get('fixed-budget-campaign', [FixedBudgetCampaignController::class, 'getFixedBudgetCampaign']);
    Route::post('save-fixed-budget-campaign', [FixedBudgetCampaignController::class, 'saveFixedBudgetCampaign']);
    Route::put('update-fixed-budget-campaign/{id}', [FixedBudgetCampaignController::class, 'updateFixedBudgetCampaign']);
    Route::delete('delete-fixed-budget-campaign/{id}', [FixedBudgetCampaignController::class, 'deleteFixedBudgetCampaign']);

    //////////////////////        CASH BACK CAMPAIGN       /////////////////////
    Route::get('cash-back-campaign', [CashBackCampaignController::class, 'getCashBackCampaign']);
    Route::post('save-cash-back-campaign', [CashBackCampaignController::class, 'saveCashBackCampaign']);
    Route::put('update-cash-back-campaign/{id}', [CashBackCampaignController::class, 'updateCashBackCampaign']);
    Route::delete('delete-cash-back-campaign/{id}', [CashBackCampaignController::class, 'deleteCashBackCampaign']);

    //////////////////////        BONUS CAMPAIGN       /////////////////////
    Route::get('bonus-campaign', [BonusCampaignController::class, 'getBonusCampaign']);
    Route::post('save-bonus-campaign', [BonusCampaignController::class, 'saveBonusCampaign']);
    Route::put('update-bonus-campaign/{id}', [BonusCampaignController::class, 'updateBonusCampaign']);
    Route::delete('delete-bonus-campaign/{id}', [BonusCampaignController::class, 'deleteBonusCampaign']);

    //////////////////////        CUMULATIVE PERIODIC CAMPAIGN       /////////////////////
    Route::get('cumulative-periodic-campaign', [CumulativePeriodicCampaignController::class, 'getCumulativePeriodicCampaign']);
    Route::post('save-cumulative-periodic-campaign', [CumulativePeriodicCampaignController::class, 'saveCumulativePeriodicCampaign']);
    Route::put('update-cumulative-periodic-campaign/{id}', [CumulativePeriodicCampaignController::class, 'updateCumulativePeriodicCampaign']);
    Route::delete('delete-cumulative-periodic-campaign/{id}', [CumulativePeriodicCampaignController::class, 'deleteCumulativePeriodicCampaign']);

    //////////////////////        FREQUENCY COUNT PERIODIC CAMPAIGN       /////////////////////
    Route::get('frequency-count-campaign', [FrequencyCountPeriodicController::class, 'getFreqCountPeriodicCampaign']);
    Route::post('save-frequency-count-campaign', [FrequencyCountPeriodicController::class, 'saveFreqCountPeriodicCampaign']);
    Route::put('update-frequency-count-campaign/{id}', [FrequencyCountPeriodicController::class, 'updateFreqCountPerCampaign']);
    Route::delete('delete-frequency-count-campaign/{id}', [FrequencyCountPeriodicController::class, 'deleteFreqCountPerCampaign']);

    //////////////////////        SPECIAL DATE CAMPAIGN       /////////////////////
    Route::get('special-date-campaign', [SpecialDateController::class, 'getSpecialDateCampaign']);
    Route::post('save-special-date-campaign', [SpecialDateController::class, 'saveSpecialDateCampaign']);
    Route::put('update-special-date-campaign/{id}', [SpecialDateController::class, 'updateSpecialDateCampaign']);
    Route::delete('delete-special-date-campaign/{id}', [SpecialDateController::class, 'deleteSpecialDateCampaign']);

    //////////////////////        CASH BACK PERIODIC CAMPAIGN       /////////////////////
    Route::get('cash-back-periodic-campaign', [CashBackPeriodicController::class, 'getcashBackPeriodicCampaign']);
    Route::post('save-cash-back-periodic-campaign', [CashBackPeriodicController::class, 'saveCashBackPeriodicCampaign']);
    Route::put('update-cash-back-periodic-campaign/{id}', [CashBackPeriodicController::class, 'updateCashBackPeriodicCampaign']);
    Route::delete('delete-cash-back-periodic-campaign/{id}', [CashBackPeriodicController::class, 'deleteCashBackPeriodicCampaign']);

    //////////////////////        BONUS PERIODIC CAMPAIGN       /////////////////////
    Route::get('bonus-periodic-campaign', [BonusPeriodicController::class, 'getBonusPeriodicCampaign']);
    Route::post('save-bonus-periodic-campaign', [BonusPeriodicController::class, 'saveBonusPeriodicCampaign']);
    Route::put('update-bonus-periodic-campaign/{id}', [BonusPeriodicController::class, 'updateBonusPeriodicCampaign']);
    Route::delete('delete-bonus-periodic-campaign/{id}', [BonusPeriodicController::class, 'deleteBonusPeriodicCampaign']);

    //////////////////////        DISCOUNT BENEFIT CAMPAIGN       /////////////////////
    Route::get('discount-benefit-campaign', [DiscountBenefitController::class, 'getDiscountBenefitCampaign']);
    Route::post('save-discount-benefit-campaign', [DiscountBenefitController::class, 'saveDiscountBenefitCampaign']);
    Route::put('update-discount-benefit-campaign/{id}', [DiscountBenefitController::class, 'updateDiscountBenefitCampaign']);
    Route::delete('delete-discount-benefit-campaign/{id}', [DiscountBenefitController::class, 'deleteDiscountBenefitCampaign']);

    //////////////////////        UPGRADE PRIVILEGE CAMPAIGN       /////////////////////
    Route::get('upgrade-privilege-campaign', [UpgradePrivilegeController::class, 'getUpgradePrivilegeCampaign']);
    Route::post('save-upgrade-privilege-campaign', [UpgradePrivilegeController::class, 'saveUpgradePrivilegeCampaign']);
    Route::put('update-upgrade-privilege-campaign/{id}', [UpgradePrivilegeController::class, 'updateUpgradePrivilegeCampaign']);
    Route::delete('delete-upgrade-privilege-campaign/{id}', [UpgradePrivilegeController::class, 'deleteUpgradePrivilegeCampaign']);

    //////////////////////        REFERAL CAMPAIGN       /////////////////////
    Route::get('referal-campaign', [ReferalCampaignController::class, 'getReferalCampaign']);
    Route::post('save-referal-campaign', [ReferalCampaignController::class, 'saveReferalCampaign']);
    Route::put('update-referal-campaign/{id}', [ReferalCampaignController::class, 'updateReferalCampaign']);
    Route::delete('delete-referal-campaign/{id}', [ReferalCampaignController::class, 'deleteReferalCampaign']);

    //////////////////////        PROMO CAMPAIGN       /////////////////////
    Route::get('promo-campaign', [PromoCampaignController::class, 'getPromoCampaign']);
    Route::post('save-promo-campaign', [PromoCampaignController::class, 'savePromoCampaign']);
    Route::put('update-promo-campaign/{id}', [PromoCampaignController::class, 'updatePromoCampaign']);
    Route::delete('delete-promo-campaign/{id}', [PromoCampaignController::class, 'deletePromoCampaign']);

    //////////////////////        FIXED BUDGET PERIODIC CAMPAIGN       /////////////////////
    Route::get('fixed-budget-periodic-campaign', [FixedBudgetPeriodicCampaignController::class, 'getFixedBudgetPeriodicCampaign']);
    Route::post('save-fixed-budget-periodic-campaign', [FixedBudgetPeriodicCampaignController::class, 'saveFixedBudgetPeriodicCampaign']);
    Route::put('update-fixed-budget-periodic-campaign/{id}', [FixedBudgetPeriodicCampaignController::class, 'updateFixedBudgetPeriodicCampaign']);
    Route::delete('delete-fixed-budget-periodic-campaign/{id}', [FixedBudgetPeriodicCampaignController::class, 'deleteFixedBudgetPeriodicCampaign']);

    //////////////////////        GAME CAMPAIGN       /////////////////////
    Route::get('game-campaign', [GameCampaignController::class, 'getGameCampaign']);
    Route::post('save-game-campaign', [GameCampaignController::class, 'saveGameCampaign']);
    Route::put('update-game-campaign/{id}', [GameCampaignController::class, 'updateGameCampaign']);
    Route::delete('delete-game-campaign/{id}', [GameCampaignController::class, 'deleteGameCampaign']);











    ///////////////////////     POINTS AWARD TRANSACTION     //////////////////////////////
    Route::get('my-membership', [PointAwardTransactionController::class, 'getMembershipId']);
    Route::get('transaction-type', [PointAwardTransactionController::class, 'getTransactionType']);
    Route::get('payment-type', [PointAwardTransactionController::class, 'getPaymentType']);
    Route::get('all-branches', [PointAwardTransactionController::class, 'getbranches']);
    Route::get('giftcard', [PointAwardTransactionController::class, 'getGiftCardT']);
    Route::post('save-points-award-transaction', [PointAwardTransactionController::class, 'savePointAwardTransaction']);

    ///////////////////////     MANUAL CREDIT TRANSACTION     //////////////////////////////
    Route::get('manual-membership-credit', [ManualCreditTransactionController::class, 'getMembershipID']);
    Route::get('manual-pin-credit', [ManualCreditTransactionController::class, 'getMemberPin']);
    Route::post('manual-credit-transaction/{id}', [ManualCreditTransactionController::class, 'saveManualCredit']);

    ///////////////////////     MANUAL DEBIT TRANSACTION     //////////////////////////////
    Route::get('manual-membership-debit', [ManualDebitTransactionController::class, 'getMembershipID']);
    Route::get('manual-pin-debit', [ManualDebitTransactionController::class, 'getMemberPin']);
    Route::post('manual-debit-transaction/{id}', [ManualDebitTransactionController::class, 'saveManualDebit']);

    ///////////////////////  PROGRAM ROUTE  ////////////////////////
    Route::get('program', [ProgramController::class, 'index']);
    Route::post('program-save', [ProgramController::class, 'store']);
    Route::get('program-show/{program}', [ProgramController::class, 'show']);
    Route::put('program-update/{program}', [ProgramController::class, 'update']);
    Route::delete('program-delete/{program}', [ProgramController::class, 'destroy']);

});
