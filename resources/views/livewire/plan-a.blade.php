<div class="router-container pb-15 container-fluid">
    <style>
        .table-headings {
            margin: 0px;
            background-color: #e9edf1;
            font-size: 14px;
            color: #7f8fa4;
            font-weight: 400;
            padding: 5px 10px;
        }

        .modal-inputs {
            width: 200px;

        }

        .modal-labels {
            font-size: 13px;
            color: #7f8fa4;
            font-weight: 500;
        }

        .label-inputs {
            padding: 10px;
        }

        .total-declaration {
            color: #7f8fa4;
        }

        .declaration-buttons {
            background-color: rgb(2, 17, 79);
            border: none;
            padding: 5px 10px;
            color: white;
            font-size: 15px;
            font-weight: 500;
            border-radius: 3px;
        }

        .declaration-clear {
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            color: rgb(2, 17, 79);
            cursor: pointer;
        }

        .declaration-clear:hover {
            text-decoration: underline;
            cursor: pointer;
        }

        .remove-house {
            display: none;
            font-size: 13px;
            padding: 0px;
            border: none;
        }

        .nav-link {
            padding: 5px;
            gap: 15px;
            width: 100px;
        }

        .nav-item:hover .remove-house {
            display: inline-block;
            font-size: 13px;
            padding: 0px;
        }

        .nav-item:hover .nav-link {
            padding: 5px;
            text-decoration: underline;
        }
    </style>
    <router-outlet></router-outlet>
    <itd-plan-details _nghost-whw-c468="" class="ng-star-inserted">
        <div _ngcontent-whw-c468="" class="itd-plan-details ng-star-inserted">
            <div _ngcontent-whw-c468="" class="itd-plan-details-alert ng-star-inserted">
                <alert _ngcontent-whw-c468="" type="info" dismissible="true">
                    <div role="alert" class="alert alert-info alert-dismissible ng-star-inserted row m-0 pe-3 ps-2">

                        <!---->
                        <div class="col-8">
                            <i _ngcontent-whw-c468="" class="alert-icon fa fa-fw fa-warning me-2"></i>
                            <span _ngcontent-whw-c468="" class="alert-text" style="font-size:12px"> Please note, you're
                                only calculating your taxes. This is not a submission. </span>
                        </div>
                        <div class="col text-end">
                            <button type="button" aria-label="Close" class="btn btn-close" style="padding: 15px 15px; top: 8px; right: 8px;">
                                <!-- <span aria-hidden="true"></span> -->
                                <span class="sr-only">Close</span>
                            </button>
                        </div>
                    </div>
                    <!---->
                </alert>
            </div>
            <div _ngcontent-whw-c468="" class="itd-plan-details-search ng-star-inserted">
                <itd-sections-typeahead _ngcontent-whw-c468="" _nghost-whw-c466="">
                    <gt-typeahead _ngcontent-whw-c466="" class="itd-sections-typeahead ng-star-inserted">
                        <div dropdown="" class="btn-group gt-typeahead">
                            <form novalidate="" class="ng-untouched ng-pristine ng-valid">
                                <div class="input-with-icon">
                                    <input type="text" name="search" placeholder="Search" autocomplete="off"
                                        class="form-control ng-untouched ng-pristine ng-valid">
                                    <!---->
                                    <i class="icon-gt-search ng-star-inserted"></i>
                                    <!---->
                                </div>
                            </form>
                            <!---->
                        </div>
                        <!---->
                    </gt-typeahead>
                    <!---->
                    <!---->
                </itd-sections-typeahead>
            </div>
            @foreach($employees as $employee )
            <div _ngcontent-whw-c468="" class="itd-plan-details-content ng-star-inserted">
                <div _ngcontent-whw-c468="" class="row">
                    <!-- Card 1 -->
                    <div class="col-md-3" style="margin-top:20px;">
                        <div class="row m-0 text-center" style="background:white;border:1px solid silver;border-radius:5px;font-size:12px">
                            <img src="images/deduction-img/80c.png"
                                style="width:80px;" class="mb-3 mt-3 m-auto">
                            <p _ngcontent-whw-c467="" class="text-black mb-2" title="Sec 80C">Sec 80C
                            </p>

                            <p class="mb-2">Total Value: {{ $total }}/1,50,000</p>

                            <a class="declaration-link" style="margin-bottom: 2em;" wire:click="addSec80">Add to declaration</a>
                        </div>
                        @if($showSec80CDialog)

                        <div class="modal" tabindex="-1" role="dialog" style="display: block;font-size:12px">

                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

                                <div class="modal-content" style="font-size:12px">

                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Sec 80C</h1>
                                        <button wire:click="closeSec80C" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: auto;">
                                        <div class="row m-0">
                                            <div class="col-md-6">
                                                <p class="fw-bold">Total Value: {{ $total}}/1,50,000</p>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <p class="fw-bold">Max Limit in (₹): 1,50,000</p>
                                            </div>
                                        </div>
                                        <!-- Begin the form outside the .form-group div -->
                                        <form wire:submit.prevent="submitsec80">
                                            <div class="row m-0">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="5_years_fixed_deposit">80C 5 Years of Fixed Deposit in Scheduled Bank</label>

                                                        <input type="text" wire:model="fields.5_years_fixed_deposit"
                                                            name="fields.5_years_fixed_deposit"
                                                            id="5_years_fixed_deposit" class="form-control"
                                                            placeholder="₹ Enter amount">


                                                        @error('fields.5_years_fixed_deposit')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="contribution_to_pension_fund">80CCC Contribution to
                                                            Pension Fund</label>
                                                        <input type="text"
                                                            wire:model="fields.contribution_to_pension_fund"
                                                            name="fields.contribution_to_pension_fund"
                                                            id="contribution_to_pension_fund" class="form-control"
                                                            placeholder="₹ Enter amount">


                                                        @error('fields.contribution_to_pension_fund')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="deposit_in_nss">80C Deposit in NSS</label>
                                                        <input type="text" wire:model="fields.deposit_in_nss"
                                                            name="fields.deposit_in_nss" id="deposit_in_nss"
                                                            class="form-control" placeholder="₹ Enter amount">


                                                        @error('fields.deposit_in_nss')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="equity">80C
                                                            Equity Linked Savings Scheme ( ELSS )</label>

                                                        <input type="text" wire:model="fields.equity"
                                                            name="fields.equity" id="equity" class="form-control"
                                                            placeholder="₹ Enter amount">

                                                        @error('fields.life_insurance')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="5_years_deposit">80C Children
                                                            Tuition Fees
                                                        </label>

                                                        <input type="text" wire:model="fields.5_years_deposit"
                                                            name="fields.5_years_deposit" id="5_years_deposit"
                                                            class="form-control" placeholder="₹ Enter amount">


                                                        @error('fields.5_years_deposit')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="deposit_in_nsc">80C Deposit in
                                                            NSC</label>


                                                        <input type="text" wire:model="fields.deposit_in_nsc"
                                                            name="fields.deposit_in_nsc" id="deposit_in_nsc"
                                                            class="form-control" placeholder="₹ Enter amount">


                                                        @error('fields.deposit_in_nsc')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="interest_on_nsc_reinvested">80C Interest on
                                                            NSC Reinvested</label>
                                                        <input type="text"
                                                            wire:model="fields.interest_on_nsc_reinvested"
                                                            name="fields.interest_on_nsc_reinvested"
                                                            id="interest_on_nsc_reinvested" class="form-control"
                                                            placeholder="₹ Enter amount">


                                                        @error('fields.deposit_in_nsc')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="life_insurance">Life Insurance
                                                            Premium</label>


                                                        <input type="text" wire:model="fields.life_insurance"
                                                            name="fields.life_insurance" id="life_insurance"
                                                            class="form-control" placeholder="₹ Enter amount">

                                                        @error('fields.life_insurance')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 20px;">
                                                <div class="col-2">
                                                    <button type="button" wire:click="submitsec80"
                                                        class="btn btn-success">Submit</button>
                                                </div>
                                                <div class="col-2">
                                                    <button wire:click="closeSec80C" class="btn btn-danger fs12">Cancel</button>
                                                </div>
                                            </div>

                                        </form>

                                        @if($total)
                                        <p>Total Value: {{ $total }}</p>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-backdrop fade show blurred-backdrop"></div>

                        @endif

                    </div>
                    <div class="col-md-3" style="margin-top:20px;">
                        <div class="row m-0 text-center" style="margin-top:20px;background:white;border:1px solid silver;border-radius:5px;font-size:12px">
                            <img src="images/deduction-img/deduction.png"
                                style="width:80px;" class="mt-3 mb-3 m-auto">
                            <p _ngcontent-whw-c467="" class="text-black mb-2" title="Sec 80C"
                                style="font-size:12px">Other Chapter VI-A Deductions</p>
                            <p class="mb-2">Total Value: {{ $totaldeductions }}/1,50,000</p>
                            <a class="declaration-link" wire:click="addshowVIDeductions" style="margin-bottom: 2em;">Add to declaration</a>
                        </div>
                        @if($showVIDeductions)

                        <div class="modal" tabindex="-1" role="dialog" style="display: block;">

                            <div class="modal-dialog modal-dialog-centered" role="document">

                                <div class="modal-content" style="width: 800px">
                                    <div class="modal-header"
                                        style="background-color: #D4D2D2; height: 60px; width: 800px">
                                        <h5 style="padding: 5px; color: #747576; font-size: 15px;" class="modal-title">
                                            <b>Other Chapter VI-A Deductions</b>
                                        </h5>
                                        <button wire:click="closeshowVIDeductions" type="button" class="close"
                                            data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="color: black;">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="max-height: auto;">
                                        <div class="container"
                                            style="height: 30px; border: 1px solid silver; border-radius: 7px;width:700px">
                                            <p>Total declared in (₹)</p>

                                        </div>
                                        <!-- Begin the form outside the .form-group div -->
                                        <form wire:submit.prevent="submitotherdeductions">
                                            <div class="form-group">
                                                <div class="column" style="display:flex">
                                                    <div class="row">
                                                        <label for="intrest_on_housing"
                                                            style="font-size:12px;margin-left:10px">80EE Interest on
                                                            housing loan borrowed as on 24th oct 2023
                                                        </label>
                                                        <input type="text"
                                                            wire:model="fieldsdeductions.intrest_on_housing"
                                                            name="fieldsdeductions.intrest_on_housing"
                                                            id="intrest_on_housing" class="form-control"
                                                            placeholder="₹ Enter amount"
                                                            style="width:200px; font-size:14px; margin-left:20px; ">
                                                        <span class="limit text-6 ng-star-inserted"
                                                            style="font-size:10px;margin-left:20px">
                                                            <span class="text-secondary text-5 text-regular">Max limit
                                                                in <i
                                                                    class="icon-gt-rupee text-6 text-regular"></i>:</span>
                                                            <span class="text-black">₹ 50,000.00</span>
                                                        </span>

                                                        @error('fieldsdeductions.intrest_on_housing')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                    <div class="row">
                                                        <label for="intrest_on_loan"
                                                            style="font-size:12px;margin-left:30px">80EEA Interest on
                                                            Housing loan borrowed as on 1st Apr 2023</label>
                                                        <input type="text" wire:model="fieldsdeductions.intrest_on_loan"
                                                            name="fieldsdeductions.intrest_on_loan"
                                                            id="intrest_on_housing" class="form-control"
                                                            placeholder="₹ Enter amount"
                                                            style="width:200px; font-size:14px; margin-left:40px; ">

                                                        <span class="limit text-6 ng-star-inserted"
                                                            style="font-size:10px;margin-left:30px">
                                                            <span class="text-secondary text-5 text-regular">Max limit
                                                                in <i
                                                                    class="icon-gt-rupee text-6 text-regular"></i>:</span>
                                                            <span class="text-black">₹ 50,000.00</span>
                                                        </span>
                                                        @error('fieldsdeductions.intrest_on_loan')
                                                        <div class="text-danger"
                                                            style="font-size:10px;margin-left:20px">{{ $message }}</div>
                                                        @enderror

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="column" style="display:flex">
                                                    <div class="row">
                                                        <label for="contribution_to_pension_fund"
                                                            style="font-size:12px;margin-left:10px">80CCD(1) Employee
                                                            Contribution to NPS</label>

                                                        <input type="text"
                                                            wire:model="fieldsdeductions.contribution_to_pension_fund"
                                                            name="fieldsdeductions.contribution_to_pension_fund"
                                                            id="contribution_to_pension_fund" class="form-control"
                                                            placeholder="₹ Enter amount"
                                                            style="width:200px; font-size:14px; margin-left:20px; ">

                                                        <span class="limit text-6 ng-star-inserted"
                                                            style="font-size:10px;margin-left:20px">
                                                            <span class="text-secondary text-5 text-regular">Max limit
                                                                in <i
                                                                    class="icon-gt-rupee text-6 text-regular"></i>:</span>
                                                            <span class="text-black">₹ 50,000.00</span>
                                                        </span>
                                                        @error('fieldsdeductions.contribution_to_pension_fund')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                    <div class="row">
                                                        <label for="deposit_in_nsc"
                                                            style="margin-left: 80px;font-size:12px">80EEB Interest on
                                                            Electric Vehicle borrowed as on 1st Apr 2023
                                                        </label>

                                                        <input type="text" wire:model="fieldsdeductions.deposit_in_nsc"
                                                            name="fieldsdeductions.deposit_in_nsc" id="deposit_in_nsc"
                                                            class="form-control" placeholder="₹ Enter amount"
                                                            style="width:200px; font-size:14px; margin-left:110px; ">

                                                        <span class="limit text-6 ng-star-inserted"
                                                            style="font-size:10px;margin-left:120px">
                                                            <span class="text-secondary text-5 text-regular">Max limit
                                                                in <i
                                                                    class="icon-gt-rupee text-6 text-regular"></i>:</span>
                                                            <span class="text-black">₹ 50,000.00</span>
                                                        </span>
                                                        @error('fieldsdeductions.deposit_in_nsc')
                                                        <div class="text-danger"
                                                            style="font-size:10px;margin-left:100px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="column" style="display:flex">
                                                    <div class="row">
                                                        <label for="deposit_in_nss" style="font-size:12px">80CCD1(B)
                                                            Contribution to NPS 2023</label>


                                                        <input type="text" wire:model="fieldsdeductions.deposit_in_nss"
                                                            name="fieldsdeductions.deposit_in_nss" id="deposit_in_nss"
                                                            class="form-control" placeholder="₹ Enter amount"
                                                            style="width:200px; font-size:14px; margin-left:20px; ">

                                                        <span class="limit text-6 ng-star-inserted"
                                                            style="font-size:10px;margin-left:40px">
                                                            <span class="text-secondary text-5 text-regular">Max limit
                                                                in <i
                                                                    class="icon-gt-rupee text-6 text-regular"></i>:</span>
                                                            <span class="text-black">₹ 50,000.00</span>
                                                        </span>

                                                        @error('fieldsdeductions.deposit_in_nss')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                    <div class="row">
                                                        <label for="interest_on_nsc_reinvested"
                                                            style="margin-left: 100px;font-size:12px">80TTB Interest on
                                                            Deposits in Savings Account, FDs for Senior Citizen</label>

                                                        <input type="text"
                                                            wire:model="fieldsdeductions.interest_on_nsc_reinvested"
                                                            name="fieldsdeductions.interest_on_nsc_reinvested"
                                                            id="interest_on_nsc_reinvested" class="form-control"
                                                            placeholder="₹ Enter amount"
                                                            style="width:200px; font-size:14px; margin-left:140px; ">

                                                        <span class="limit text-6 ng-star-inserted"
                                                            style="font-size:10px;margin-left:130px">
                                                            <span class="text-secondary text-5 text-regular">Max limit
                                                                in <i
                                                                    class="icon-gt-rupee text-6 text-regular"></i>:</span>
                                                            <span class="text-black">₹ 50,000.00</span>
                                                        </span>
                                                        @error('fieldsdeductions.interest_on_nsc_reinvested')
                                                        <div class="text-danger"
                                                            style="font-size:10px;margin-left:110px">{{ $message }}
                                                        </div>
                                                        @enderror


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="column" style="display:flex">
                                                    <div class="row">
                                                        <label for="superannuation" style="font-size:12px">10(13)
                                                            Superannuation Exemption</label>

                                                        <input type="text" wire:model="fieldsdeductions.superannuation"
                                                            name="fieldsdeductions.superannuation" id="superannuation"
                                                            class="form-control" placeholder="₹ Enter amount"
                                                            style="width:200px; font-size:14px; margin-left:20px; ">

                                                        <span class="limit text-6 ng-star-inserted"
                                                            style="font-size:10px;margin-left:20px">
                                                            <span class="text-secondary text-5 text-regular">Max limit
                                                                in <i
                                                                    class="icon-gt-rupee text-6 text-regular"></i>:</span>
                                                            <span class="text-black">₹ 50,000.00</span>
                                                        </span>
                                                        @error('fieldsdeductions.superannuation')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>
                                                    <div class="row">
                                                        <label for="donation"
                                                            style="margin-left: 90px;font-size:12px">80G Donation - 100%
                                                            Exemption</label>

                                                        <input type="text" wire:model="fieldsdeductions.donation"
                                                            name="fieldsdeductions.donation" id="donation"
                                                            class="form-control" placeholder="₹ Enter amount"
                                                            style="width:200px; font-size:14px; margin-left:110px; ">

                                                        <span class="limit text-6 ng-star-inserted"
                                                            style="font-size:10px;margin-left:110px">
                                                            <span class="text-secondary text-5 text-regular">Max limit
                                                                in <i
                                                                    class="icon-gt-rupee text-6 text-regular"></i>:</span>
                                                            <span class="text-black">₹ 50,000.00</span>
                                                        </span>
                                                        @error('fieldsdeductions.donation')
                                                        <div class="text-danger"
                                                            style="font-size:10px;margin-left:90px">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 20px;">
                                                <div class="col-1" style="margin-left: 30%;">
                                                    <button wire:click="submitotherdeductions"
                                                        class="custom-button submit-button"
                                                        style="background:green;border:1px solid silver;border-radius:5px;color:white;margin-left:-200px;width:80px;height:30px;">Submit</button>
                                                </div>
                                                <div class="col-2" style="margin-left: 10%;">
                                                    <button wire:click="closeshowVIDeductions"
                                                        class="custom-button cancel-button"
                                                        style="background:red;border:1px solid silver;border-radius:5px;color:white;margin-left:-190px;width:80px;height:30px">Cancel</button>
                                                </div>
                                                @if($totaldeductions)
                                                <p>Total Value: {{ $totaldeductions }}</p>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-backdrop fade show blurred-backdrop"></div>

                        @endif
                    </div>
                    <div class="col-md-3" style="margin-top:20px;">
                        <div class="row m-0 text-center" style="background:white;border:1px solid silver;border-radius:5px;font-size:12px">
                            <img src="images/it-declaration-home.png"
                                style="width:80px;" class="mb-3 mt-3 m-auto">
                            <p _ngcontent-whw-c467="" class="text-black mb-2" title="Sec 80C">House Rent Allowance
                            </p>
                            <a class="declaration-link" style="margin-bottom: 4em;" wire:click="addMedicalSec80">Add to declaration</a>
                        </div>
                        @if($showMedicalSec80CDialog)
                        <div class="modal" tabindex="-1" role="dialog" style="display: block;font-size:12px">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content" style="font-size:12px">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">House Rent Allowance</h1>
                                        <button wire:click="closeMedicalSec80" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body" style="max-height: auto;">
                                        <ul class="nav nav-tabs">
                                            @foreach ($houses as $index => $house)
                                            <div class="nav-item">
                                                <li class=" nav-link d-flex {{ $selectedHouseIndex === $index ? 'active' : '' }}" style="font-size: 13px;">
                                                    <!-- House Tab Button -->
                                                    <button style="border: none;background-color:white !important;"
                                                        wire:click="selectHouse({{ $index }})">
                                                        House {{ $index + 1 }}
                                                    </button>

                                                    <!-- Remove House Button -->
                                                    <button class="btn btn-sm remove-house "
                                                        style="top: 5px; right: 5px;"
                                                        wire:click="removeHouse({{ $index }})">
                                                        x
                                                    </button>
                                                </li>
                                            </div>
                                            @endforeach
                                            <a style="margin-left: auto;" href="#" wire:click="addHouse">+ Add New House</a>
                                        </ul>


                                        <!-- Body Content for Selected House -->
                                        <div class="tab-content mt-3">
                                            <div class="tab-pane fade show active">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="from-{{ $selectedHouseIndex }}" class="form-label">From <span class="text-danger">*</span></label>
                                                                <select id="from-{{ $selectedHouseIndex }}" wire:model="houses.{{ $selectedHouseIndex }}.from" class="form-select">
                                                                    <option value="Apr 2024">Apr 2024</option>
                                                                    <option value="May 2024">May 2024</option>
                                                                    <option value="Jun 2024">Jun 2024</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="to-{{ $selectedHouseIndex }}" class="form-label">To <span class="text-danger">*</span></label>
                                                                <select id="to-{{ $selectedHouseIndex }}" wire:model="houses.{{ $selectedHouseIndex }}.to" class="form-select">
                                                                    <option value="Mar 2025">Mar 2025</option>
                                                                    <option value="Apr 2025">Apr 2025</option>
                                                                    <option value="May 2025">May 2025</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <label for="monthly-rent-{{ $selectedHouseIndex }}" class="form-label">Monthly Rent Amount <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text">₹</span>
                                                                    <input type="number" id="monthly-rent-{{ $selectedHouseIndex }}" wire:model="houses.{{ $selectedHouseIndex }}.monthly_rent" class="form-control" placeholder="Enter amount">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Annual Rent Amount ₹:</label>
                                                                <div>{{ is_numeric($houses[$selectedHouseIndex]['monthly_rent']) ? (int)$houses[$selectedHouseIndex]['monthly_rent'] * 12 : 0 }}</div>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <label for="address-{{ $selectedHouseIndex }}" class="form-label">Full Address</label>
                                                                <textarea id="address-{{ $selectedHouseIndex }}" wire:model="houses.{{ $selectedHouseIndex }}.address" class="form-control" rows="2" placeholder="Enter full address"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="modal-footer d-flex text-center " style="max-height: auto;justify-content:center;gap:10px">
                                        <button class="declaration-buttons">Save</button>
                                        <a class="declaration-clear">Clear Form</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show blurred-backdrop"></div>

                        @endif
                    </div>

                    <div class="col-md-3" style="margin-top:20px;">
                        <div class="row m-0 text-center" style="background:white;border:1px solid silver;border-radius:5px;font-size:12px">
                            <img src="images/deduction-img/80d.png"
                                style="width:80px;" class="mt-3 mb-3 m-auto">
                            <p _ngcontent-whw-c467="" class="text-black mb-2" title="Sec 80C">Medical
                                (Sec 80D)

                            </p>
                            <a class="declaration-link" style="margin-bottom: 4em;" wire:click="addMedical">Add to declaration</a>
                        </div>
                        @if($showMedicalDialog)

                        <div class="modal" tabindex="-1" role="dialog" style="display: block;">

                            <div class="modal-dialog modal-dialog-centered" role="document">

                                <div class="modal-content" style="width: 800px">
                                    <div class="modal-header"
                                        style="background-color:#D4D2D2; height: 60px; width: 800px">
                                        <h5 style="padding: 5px; color: #747576; font-size: 15px;" class="modal-title">
                                            <b>Medical (Sec 80D)</b>
                                        </h5>
                                        <button wire:click="closeMedical" type="button" class="close"
                                            data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="color: white;">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="max-height: auto;overflow-y: auto;">
                                        <div class="container"
                                            style="height: 30px; border: 1px solid silver; border-radius: 7px;width:700px">
                                            <p>Total declared in (₹)</p>

                                        </div>
                                        <!-- Begin the form outside the .form-group div -->
                                        <form wire:submit.prevent="submit">
                                            <div class="form-group">
                                                <div class="column" style="display:flex">

                                                    <div class="row" style="margin-top:20px">
                                                        <div class="container"
                                                            style="height:30px;background:#D9D9D9;width:780px;border:1px solid silver">
                                                            80D Medical Bills - Senior Citizen (>60)

                                                        </div>
                                                        <label for="Medical" style="font-size:14px">declared
                                                            amount</label>

                                                        <input type="text" wire:model="fieldsmedical.medical"
                                                            name="fieldsmedical.medical" id="medical"
                                                            class="form-control" placeholder="₹ Enter amount"
                                                            style="width:200px; font-size:14px; margin-left:10px;">

                                                        @error('fields.health_checkup')
                                                        <div class="text-danger" style="font-size:10px">{{ $message }}
                                                        </div>
                                                        @enderror

                                                        <b style="font-size:10px;color:#C3C1C1">Max limit in
                                                            :₹50,000.00</b>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row" style="margin-top:20px">
                                                    <div class="container"
                                                        style="height:30px;background:#D9D9D9;width:780px;border:1px solid silver">
                                                        Preventive Health Check-up

                                                    </div>
                                                    <label for="Health Checkup" style="font-size:14px">declared
                                                        amount</label>

                                                    <input type="text" wire:model="fields.health_checkup"
                                                        name="fields.health_checkup" id="health_checkup"
                                                        class="form-control" placeholder="₹ Enter amount"
                                                        style="width:200px; font-size:14px; margin-left:10px; ">

                                                    @error('fields.health_checkup')
                                                    <div class="text-danger" style="font-size:10px">{{ $message }}</div>
                                                    @enderror
                                                    <b style="font-size:10px;color:#C3C1C1">Max limit in :₹50,000.00</b>
                                                </div>

                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="column" style="display:flex">
                                            <div class="row" style="margin-top:-10px">
                                                <div class="container"
                                                    style="height:30px;background:#D9D9D9;width:780px;border:1px solid silver">
                                                    80D Preventive Health Checkup - Dependant Parents

                                                </div>
                                                <label for="Dependant Parents"
                                                    style="font-size:14px;margin-left:10px">declared amount</label>

                                                <input type="text" wire:model="fieldsmedical.Dependant Parents"
                                                    name="fieldsmedical.Dependant Parents" id="Dependant Parents"
                                                    class="form-control" placeholder="₹ Enter amount"
                                                    style="width:200px; font-size:14px; margin-left:20px; ">

                                                @error('fields.Dependant Parents')
                                                <div class="text-danger" style="font-size:10px">{{ $message }}</div>
                                                @enderror
                                                <b style="font-size:10px;color:#C3C1C1;margin-left:10px">Max limit in
                                                    :₹50,000.00</b>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: -10px;">
                                        <div class="col-1" style="margin-left: 30%;">
                                            <button type="button" wire:click="submitmedical"
                                                class="custom-button submit-button"
                                                style="background:green;border:1px solid silver;border-radius:5px;color:white;margin-left:-100px;width:80px;">Submit</button>
                                        </div>
                                        <div class="col-2" style="margin-left: 10%;">
                                            <button wire:click="closeMedical" class="custom-button cancel-button"
                                                style="background:red;border:1px solid silver;border-radius:5px;color:white;margin-left:-70px;width:80px;">Cancel</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-backdrop fade show blurred-backdrop"></div>
                    @endif


                </div>


            </div>
            <div _ngcontent-whw-c468="" class="itd-plan-details-content ng-star-inserted">
                <div _ngcontent-whw-c468="" class="row">
                    <!-- Card 1 -->
                    <div class="col-md-3" style="margin-top:20px;">
                        <div class="row m-0 text-center" style="background:white;border:1px solid silver;border-radius:5px;font-size:12px">
                            <img src="images/deduction-img/house.png"
                                style="width:80px;" class="mt-3 mb-3 m-auto">
                            <p _ngcontent-whw-c467="" class="text-black mb-2" title="Sec 80C">
                                Income/loss from House Property</p>
                            <a class="declaration-link" style="margin-bottom: 2.5em;" wire:click="addIncome">Add to declaration</a>
                        </div>

                        @if($showIncomeDialog)
                        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="width: 800px">
                                    <div class="modal-header"
                                        style="background-color:#D4D2D2 ;height: 60px; width: 800px">
                                        <h5 style="padding:5px; color:black; font-size: 15px;" class="modal-title">
                                            <b>Income/loss from House Property</b>
                                        </h5>
                                        <button wire:click="closeIncome" type="button" class="close"
                                            data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="color: black;">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="max-height: auto;overflow-y:auto">
                                        <div class="container"
                                            style="height: 60px; border: 1px solid silver; border-radius: 7px;width:700px;display:flex">
                                            <div class="row" style="display: flex;width:200px;margin-top:10px">
                                                <p style="width:300px;font-size:14px;">Total declared in (₹)</p>
                                            </div>
                                            <div class="row" style="display: flex;margin-left:40px;font-size:10px; ">
                                                <p style="margin-left: 26px;">a. Interest on Housing Loan (Self-
                                                    Occupied) </p>
                                                <p style="margin-left: 26px;margin-top:-20px;">b. Total Income/Loss from
                                                    Let-out Property </p>
                                                <p style="margin-left: 26px;margin-top:-20px;">If (a + b) is less than
                                                    -200000 then -200000 will be exempted, else (c) will be exempted
                                                </p>
                                            </div>

                                        </div>
                                        <!-- Begin the form outside the .form-group div -->
                                        <form wire:submit.prevent="submit">
                                            <div class="form-group">
                                                <div class="column" style="display:flex">

                                                    <div class="row" style="margin-top:20px">
                                                        <div class="container"
                                                            style="height:30px;background:#D9D9D9;width:780px;border:1px solid silver">
                                                            a. Income from Self-Occupied Property

                                                        </div>
                                                        <label for="income" style="font-size:14px">Interest on Housing
                                                            Loan (Self Occupied) in ₹</label>
                                                        <input type="text" wire:model="fields.income" id="income"
                                                            class="form-control" placeholder="Enter amount"
                                                            style="width:300px;font-size:14px;margin-left:10px">
                                                        <b style="font-size:10px;color:#C3C1C1">Eligible Amount in
                                                            :₹2,00,000.00</b>
                                                        <div class="column" style="display:flex">
                                                            <div>
                                                                <label for="Lender’s"
                                                                    style="font-size:14px;margin-left:10px">Lender’s
                                                                    Name</label>
                                                                <input type="text" wire:model="fields.Lender’s"
                                                                    id="Lender’s" class="form-control"
                                                                    placeholder="Enter amount"
                                                                    style="width:300px;font-size:14px;margin-left:10px">
                                                            </div>
                                                            <div>
                                                                <label for="Lender’s plan"
                                                                    style="font-size:14px">Lender’s PAN</label>
                                                                <input type="text" wire:model="fields.Lender’s plan"
                                                                    id="Lender’s plan" class="form-control"
                                                                    placeholder="e.g.AXPRA123"
                                                                    style="width:300px;font-size:14px;margin-left:10px">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row" style="margin-top:10px">
                                                    <div class="container"
                                                        style="height:30px;background:#D9D9D9;width:780px;border:1px solid silver">
                                                        b. Income from Let-out Property

                                                    </div>
                                                    <label for="annual" style="font-size:14px">1. Annual Letable
                                                        Value/Rent Received or Receivable</label>
                                                    <input type="text" wire:model="annual" id="annual"
                                                        class="form-control" placeholder="Enter amount"
                                                        style="width:300px;font-size:14px;margin-left:10px">
                                                    <b style="font-size:10px;color:#C3C1C1">Max limit in :₹50,000.00</b>
                                                </div>
                                            </div>
                                            <!-- <label for="5_years_fixed_deposit" style="font-size:14px">1. Annual Letable Value/Rent Received or Receivable</label>
                                <input type="text" wire:model="fields.5_years_fixed_deposit" id="5_years_fixed_deposit" class="form-control" placeholder="Enter amount" style="width:300px;font-size:14px;margin-left:10px">
                                <b style="font-size:10px;color:#C3C1C1">Max limit in  :₹50,000.00</b> -->
                                    </div>

                                </div>
                            </div>


                            <div class="row" style="margin-top: -90px;">
                                <div class="col-1" style="margin-left: 30%;">
                                    <button type="submit" class="custom-button submit-button"
                                        style="background:green;border:1px solid silver;border-radius:5px;color:white;width:80px;margin-top:10px">Submit</button>
                                </div>
                                <div class="col-2">
                                    <button wire:click="closeIncome" class="custom-button cancel-button"
                                        style="background:red;border:1px solid silver;border-radius:5px;color:white;width:80px;margin-top:10px">Cancel</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <div class="modal-backdrop fade show blurred-backdrop"></div>
                        @endif

                    </div>
                    <div class="col-md-3" style="margin-top:20px;">
                        <div class="row m-0 text-center" style="background:white;border:1px solid silver;border-radius:5px;font-size:12px">
                            <img src="images/deduction-img/wage.png" style="width:80px;" class="mt-3 mb-3 m-auto">
                            <p _ngcontent-whw-c467="" class="text-black mb-2" title="Sec 80C">Other Income </p>
                            <a class="declaration-link" style="margin-bottom: 4em;" wire:click="addshowOtherIncome">Add to declaration</a>
                        </div>
                        @if($showOtherIncome)
                        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="width: 800px">
                                    <div class="modal-header" style="background-color: #D4D2D2; height: 60px; width: 800px">
                                        <h5 style="padding: 5px;  font-size: 15px;" class="modal-title"><b>Other Income</b>
                                        </h5>
                                        <button wire:click="closeshowOtherIncome" type="button" class="close"
                                            data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="color: black;">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="max-height: auto;">
                                        <div class="container"
                                            style="height: 60px; border: 1px solid silver; border-radius: 7px;width:700px;display:flex">
                                            <div class="row" style="display: flex;width:200px;margin-top:10px">
                                                <p style="width:300px;font-size:14px;">Total declared in (₹)</p>
                                            </div>
                                        </div>
                                        <!-- Begin the form outside the .form-group div -->
                                        <form wire:submit.prevent="submit">
                                            <div class="form-group">
                                                <div class="column" style="display:flex">

                                                    <div class="row" style="margin-top:20px">
                                                        <div class="container"
                                                            style="height:30px;background:#D9D9D9;width:780px;border:1px solid silver">
                                                            Other Income 1
                                                        </div>
                                                        <div class="column" style="display:flex">
                                                            <div class="row">
                                                                <label for="income"
                                                                    style="font-size:14px">Particulars</label>
                                                                <input type="text" wire:model="fields.income" id="income"
                                                                    class="form-control" placeholder="Enter income type"
                                                                    style="width:300px;font-size:14px;margin-left:10px">

                                                            </div>

                                                            <div class="row">
                                                                <label for="Lender’s"
                                                                    style="font-size:14px;margin-left:10px">Declared
                                                                    Amount</label>
                                                                <input type="text" wire:model="fields.Lender’s"
                                                                    id="Lender’s" class="form-control"
                                                                    placeholder="Enter amount "
                                                                    style="width:300px;font-size:14px;margin-left:10px">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="container"
                                                    style="height:30px;background:#D9D9D9;width:780px;border:1px solid silver;margin-top:5px;margin-left:-10px">
                                                    Other Income 2
                                                </div>
                                                <div class="column" style="display:flex">
                                                    <div class="row">
                                                        <label for="income" style="font-size:14px">Particulars</label>
                                                        <input type="text" wire:model="fields.income" id="income"
                                                            class="form-control" placeholder="Enter Income Type"
                                                            style="width:300px;font-size:14px;margin-left:10px">

                                                    </div>

                                                    <div class="row">
                                                        <label for="Lender’s"
                                                            style="font-size:14px;margin-left:10px">Declared Amount</label>
                                                        <input type="text" wire:model="fields.Lender’s" id="Lender’s"
                                                            class="form-control" placeholder="Enter amount"
                                                            style="width:300px;font-size:14px;margin-left:10px">
                                                    </div>
                                                </div>



                                            </div>
                                    </div>
                                    <div class="row" style="margin-top: -10px;">
                                        <div class="col-1">
                                            <button type="submit" class="custom-button submit-button"
                                                style="background:green;border:1px solid silver;border-radius:5px;color:white;margin-left:10px;width:80px;">Submit</button>
                                        </div>
                                        <div class="col-2" style="margin-left: 10%;">
                                            <button wire:click="closeshowOtherIncome" class="custom-button cancel-button"
                                                style="background:red;border:1px solid silver;border-radius:5px;color:white;margin-left:10px">Cancel</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                        <div class="modal-backdrop fade show blurred-backdrop"></div>
                        @endif
                    </div>

                    <div class="col-md-3" style="margin-top:20px;">
                        <div class="row m-0 text-center" style="background:white;border:1px solid silver;border-radius:5px;font-size:12px">
                            <img src="images/deduction-img/salary.png"
                                style="width:80px;" class="mt-3 mb-3 m-auto">
                            <p _ngcontent-whw-c467="" class="text-black mb-2" title="Sec 80C">Salary
                                Allowance

                            </p>
                            <a class="declaration-link" style="margin-bottom: 4em;" wire:click="addSalayAllowance">Add to declaration</a>
                        </div>
                        @if($showSalayAllowance)
                        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="width: 800px">
                                    <div class="modal-header" style="background-color: #D4D2D2; height: 60px; width: 800px">
                                        <h5 style="padding: 5px;  font-size: 15px;" class="modal-title"><b>Salary
                                                Allowance</b></h5>
                                        <button wire:click="closeSalayAllowance" type="button" class="close"
                                            data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="max-height: auto;">
                                        <div class="container"
                                            style="height: 60px; border: 1px solid silver; border-radius: 7px;width:700px;display:flex">
                                            <div class="row" style="display: flex;width:200px;margin-top:10px">
                                                <p style="width:300px;font-size:14px;">Total declared in (₹)</p>
                                            </div>


                                        </div>
                                        <!-- Begin the form outside the .form-group div -->
                                        <form wire:submit.prevent="submit">
                                            <div class="form-group">
                                                <div class="column" style="display:flex">

                                                    <div class="row" style="margin-top:20px">
                                                        <div class="container"
                                                            style="height:30px;background:#D9D9D9;width:780px;border:1px solid silver">
                                                            Other Income 1
                                                        </div>
                                                        <div class="column" style="display:flex">
                                                            <div class="row">
                                                                <label for="income"
                                                                    style="font-size:14px">Particulars</label>
                                                                <input type="text" wire:model="fields.income" id="income"
                                                                    class="form-control" placeholder="Enter income type"
                                                                    style="width:300px;font-size:14px;margin-left:10px">

                                                            </div>

                                                            <div class="row">
                                                                <label for="Lender’s"
                                                                    style="font-size:14px;margin-left:10px">Declared
                                                                    Amount</label>
                                                                <input type="text" wire:model="fields.Lender’s"
                                                                    id="Lender’s" class="form-control"
                                                                    placeholder="Enter amount "
                                                                    style="width:300px;font-size:14px;margin-left:10px">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="column" style="display:flex">
                                                    <div class="row">
                                                        <label for="income" style="font-size:14px">LTA</label>
                                                        <input type="text" wire:model="fields.income" id="income"
                                                            class="form-control" placeholder="Enter Income Type"
                                                            style="width:300px;font-size:14px;margin-left:10px">
                                                        <p>Max limit in ₹:99,99,99,999.00</p>
                                                    </div>


                                                </div>



                                            </div>
                                    </div>
                                    <div class="row" style="margin-top: -10px;">
                                        <div class="col-1">
                                            <button type="submit" class="custom-button submit-button"
                                                style="background:green;border:1px solid silver;border-radius:5px;color:white;margin-left:10px;width:80px">Submit</button>
                                        </div>
                                        <div class="col-2" style="margin-left:10%">
                                            <button wire:click="closeSalayAllowance" class="custom-button cancel-button"
                                                style="background:red;border:1px solid silver;border-radius:5px;color:white;margin-left:120x;width:80px">Cancel</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                        <div class="modal-backdrop fade show blurred-backdrop"></div>
                        @endif
                    </div>
                    <div class="col-md-3" style="margin-top:20px;">
                        <div class="row m-0 text-center" style="background:white;border:1px solid silver;border-radius:5px;font-size:12px">
                            <img src="images/it-declaration-deductions.png" style="width:80px;" class="mt-3 mb-3 m-auto">
                            <p _ngcontent-whw-c467="" class="text-black mb-2" title="Sec 80C">TCS/TDS Deduction </p>
                            <a class="declaration-link" style="margin-bottom: 4em;" wire:click="addTcsTdsDeductions">Add to declaration</a>
                        </div>
                        @if($showTcsTdsDeductions)
                        <div class="modal" tabindex="-1" role="dialog" style="display: block;font-size:12px">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content" style="font-size:12px">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">TCS/TDS Deduction</h1>
                                        <button wire:click="closeTcsTdsDeductions" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height:auto ;">
                                        <div class="row " style="border: 1px solid silver;border-radius:3px;margin:10px">
                                            <div class=" text-right" style="width: fit-content; border-right:1px solid silver;padding:10px">
                                                <span class="total-declaration">Total declared in ₹ </span>
                                                <p class=" text-end m-0" style="font-weight: bold;">- </p>
                                            </div>
                                            <p class="table-headings">TCS Deduction</p>
                                            <div class="label-inputs">
                                                <label for="5_years_fixed_deposit" class="modal-labels">Declared Amount</label>

                                                <input type="text" wire:model=""
                                                    name="fields.5_years_fixed_deposit"
                                                    id="5_years_fixed_deposit" class="form-control modal-inputs"
                                                    placeholder="₹ Enter amount">
                                            </div>
                                            <p class="table-headings">TDS Deduction</p>
                                            <div class="label-inputs">
                                                <label for="5_years_fixed_deposit" class="modal-labels">Declared Amount</label>

                                                <input type="text" wire:model=""
                                                    name="fields.5_years_fixed_deposit"
                                                    id="5_years_fixed_deposit" class="form-control modal-inputs"
                                                    placeholder="₹ Enter amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex text-center " style="max-height: auto;justify-content:center;gap:10px">
                                        <button class="declaration-buttons">Save</button>
                                        <a class="declaration-clear">Clear Form</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show blurred-backdrop"></div>
                        @endif
                    </div>
                </div>
                <!-- Card 2 -->
                <!-- Add more cards here as needed -->

            </div>
            @endforeach
        </div>


        <!---->
        <!---->
        <!---->

    </itd-plan-details>
    <!---->
    <div class="bg-white mt-3 mx-0 p-3 row text-end">
        <div>

            <button class="btn btn-outline-primary">
                <a href="/itstatement">View IT Calculation</a>
            </button>
        </div>

    </div>
</div>

<script>
    // Get the modal and buttons
    var modal = document.getElementById("myModal");
    var addToDeclarationLink = document.getElementById("addToDeclarationLink");
    var closeModalButton = document.getElementById("closeModal");

    // Open the modal when the link is clicked
    addToDeclarationLink.addEventListener("click", function() {
        modal.style.display = "block";
    });

    // Close the modal when the "Close" button is clicked
    closeModalButton.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Close the modal when clicking outside of it
    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
</script>
