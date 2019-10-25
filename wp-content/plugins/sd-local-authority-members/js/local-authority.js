(function ($) {
    
    
        function loadOperators() {
    
            var items = getOperatorListItems();
    
            var $select = $("#sel-operator");
    
            $.each(items, function (i, item) {
                $select.append($('<option>', {
                    value: item.toLowerCase(),
                    text: item
                }));
            });
        }
    
        function loadCounties(dataTable) {
    
            var column = dataTable.column(10);
            var $select = $("#sel-county");
    
            var collator = new Intl.Collator(undefined, {numeric: true, sensitivity: 'base'});

            column.data().unique().sort(collator.compare).each(function (d, j) {
                if (d) {
                    $select.append($('<option>', {
                        value: d.toLowerCase(),
                        text: d
                    }))
                }
            });
    
        }
    
        function bindOperatorSearch(dataTable) {
    
            var column = dataTable.column(1);
            var $select = $("#sel-operator");
    
            $select
                .change(function () {
                    searchColumn(column, $select)
                });
    
        }
    
        function bindCountySearch(dataTable) {
            var column = dataTable.column(10);
            var $select = $("#sel-county");
    
            $select
                .change(function () {
                    searchColumn(column, $select)
                });
        }
    
        function searchColumn(column, $select) {
            var val = $select.val();
            if (val) {
                val = $.fn.dataTable.util.escapeRegex(
                    val
                );
            }
    
            column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
    
        }
    
    
        function preselectFromQueryString() {
    
            var operators = getOperatorListItems();
            var operator = getQueryStringValue("operator");
            if (operator) {
                if (operators.includes(operator)) {
                    $("#sel-operator")
                        .val(operator.toLowerCase())
                        .change();
                }
            }
    
            var county = getQueryStringValue("county");
            if (county ) {
                $("#sel-county")
                    .val(county.toLowerCase())
                    .change();
            }
        }
    
        function getQueryStringValue(paramName) {
    
    
            var regex = new RegExp("[?&]" + paramName + "=([^&]*)", "g");
            var paramValue = regex.exec(location.href);
    
            if (paramValue) {
                return paramValue[1];
            }
    
            return false;
    
        }
    
        $(document).ready(function () {
    
            var dataTable = $('#myTable').DataTable(
                {
                    'aaData': tableData,
                    'pageLength': 15,
                    "oLanguage": {
                        "sSearch": "<span class='fa fa-search'></span>"
                    },
					"columnDefs": [
						{
							"targets": [ 1 ],
							"visible": false,
							"searchable": false
						}
                    ],
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'copy',
                        exportOptions: {
                            columns: [':visible']
                        }
                    }, {
                            extend: 'csv',
                        exportOptions: {
                            columns: [':visible']
                        }
                    }, {
                        extend: 'excel',
                        exportOptions: {
                            columns: [':visible']
                        }
                    }, {
                        extend: 'pdf',
                        orientation: 'landscape',
                        exportOptions: {
                            columns: [':visible'],
                        }
                    }]
                });
    
            loadOperators();
            loadCounties(dataTable);
            // console.log(loadCounties(dataTable));

            bindOperatorSearch(dataTable);
            bindCountySearch(dataTable);
    
            preselectFromQueryString();
        });
    
        $('#myTable').on('draw.dt', function () {
            var $searchInput = $(".member-list .dataTables_wrapper .dataTables_filter input[type=search]");
            // console.log($searchInput);
            $searchInput.attr("placeholder", "Search");
        });
    
        $('#myTable_next, #myTable_previous').on('click', function() {
            $('html,body').animate({
                scrollTop: 0}, 'slow'
            );
        });
    
    })(jQuery);
    