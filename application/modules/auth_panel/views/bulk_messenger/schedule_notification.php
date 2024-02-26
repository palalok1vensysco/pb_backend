<section class="panel">
     <div class="panel-body">
        <div class="">
            <div class="col-md-12 well allUsers">
                <div class="row pdfRow">
                    <div class="col-sm-3">
                        <label for="">Device Type</label>
                        <select name="" class="form-control search-input-select input-xs" data-column="1">
                            <option value="">All</option>
                            <option value="1">Android</option>
                            <option value="2">IOS</option>
                        </select>
                    </div>
                  
                    <div class="col-sm-3">
                        <label class="d-block" for="Search">&nbsp</label>
                        <button class="btn btn-primary apply_search">Search</button>
                    </div>
                </div>
            </div>
          <table class="hover table  mdl-data-table" id="all-user-grid" style="width:100% !important">
                <thead>
                     <tr>
                        <th>#</th>
                        <th>Id</th>
                        <th>Device Type</th>
                        <th>Notification Type</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Notification Text</th>
                        <th>Schedule Time</th>
                        <th>Created On</th>
                        <th>Action </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>


<link href="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-1.13.5/datatables.min.js"></script>
<script type="text/javascript" language="javascript">
    jQuery(document).ready(function() {

         function format(d) {
            return (
                `<div class="details">
                    <strong>Title: </strong>` + d[4] + `</br>
                     <strong>Notification Text: </strong>` + d[6] + `</br>
                    <strong>Created On: </strong>` + d[8] +
                `</div>`
            );
        }
        var all_user_csv=  all_batch_all = "<?= AUTH_PANEL_URL ?>bulk_messenger/push_notification/ajax_schedule_notification_list";
        var table = 'all-user-grid';
        var dataTable = jQuery("#" + table).DataTable({
            processing: true,
            pageLength: 15,
            lengthMenu: [
                [15, 25, 50],
                [15, 25, 50]
            ],
            serverSide: true,
            columns: [{
                    className: 'dt-control',
                    orderable: true,
                    data: null,
                    defaultContent: ""
                },
                {
                    targets: ['_all'],
                    className: 'mdc-data-table__cell',
                }
            ],
            ordering: false,
        
            aoColumnDefs: [{
                    bSortable: false,
                    aTargets: [0, 1, 2, 3, 5, 6, 7,8,9]
                },
                {
                    target: [4,6,8],
                    visible: false
                }
            ],
            ajax: {
                url: all_batch_all,
                type: "post", // method  , by default get
                error: function() { // error handling
                    jQuery("." + table + "-error").html("");
                    jQuery("#" + table + "_processing").css("display", "none");
                }
            }
        });
       jQuery("#" + table + "_filter").css("display", "none");
        $('.apply_search').on('click', function() { // for select box
            $(".search-input-text,.search-input-select").each(function() {
                var i = $(this).attr('data-column'); // getting column index
                var v = $(this).val(); // getting search input value
                dataTable.columns(i).search(v);
            });
            dataTable.draw();
        });
        dataTable.on('click', 'td.dt-control', function(e) {
            let tr = e.target.closest('tr');
            let row = dataTable.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
            } else {
                row.child(format(row.data())).show();
            }
        });
        
    });
     
</script>