@extends('admin.admin_layouts')
@section('admin_content')

<div class="container-fluid">
    <!-- Page header section  -->

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                

                <div class="container-fluid">
                    <div id="printableArea">
                       

                        <div class="row">
                            <div class="col-12" id="chart-container">
                                <div class="card">
                                    <div class="scrollbar-inner">
                                        <div id="chart-sales" data-color="primary" data-height="300"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0" id="dataTable-manual">
                                                    <thead>
                                                        <tr>
                                                            <th>Category</th>
                                                            <th>January</th>
                                                            <th>February</th>
                                                            <th>March</th>
                                                            <th>April</th>
                                                            <th>May</th>
                                                            <th>June</th>
                                                            <th>July</th>
                                                            <th>August</th>
                                                            <th>September</th>
                                                            <th>October</th>
                                                            <th>November</th>
                                                            <th>December</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="13" class="text-dark"><span>Payment :</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="13" class="text-dark"><span>Bill :</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="13" class="text-dark"><span>Expense = Payment +
                                                                    Bill :</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-dark">Total</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                            <td>₹0.00</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="body">
                            <div id="real_time_chart" class="flot-chart"></div>
                        </div>
                    </div>
                </div>


            </div>

        </div>

        @endsection