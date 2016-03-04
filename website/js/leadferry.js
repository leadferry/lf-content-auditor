/*
 *
 *   INSPINIA - Responsive Admin Theme
 *   version 2.4
 *
 */


$(document).ready(function () {




			//slick Carousel
            $('.slick_demo_1').slick({
                dots: false
            });

            //Data Table
            $('.dataTables-example').DataTable({
                "searching": false,
                paging: false,
                "info": false
            });//dashboard
            $('.dataTables-content').DataTable({
                paging: true
            });//content-overview
            $('.dataTables-vnext, .dataTables-vbefore').DataTable({
                "searching": false,
                paging: false,
                "ordering": false,
                "info": false
            });//content-detail
            $('.savedTemplates').DataTable({
                "searching": false,
                paging: true,
                "info": false
            });//report

            //Plans toggle buttons
             $('.toggle-btn button').click( function () {
                $('.toggle-btn button').addClass('btn-primary-outline');
                $(this).removeClass('btn-primary-outline');
            });//Plans toggle buttons
            
            


            //C3
            c3.generate({
                bindto: '#gauge',
                data:{
                    columns: [
                        ['data', 91.4]
                    ],

                    type: 'gauge'
                },
                color:{
                    pattern: ['#1ab394', '#BABABA']

                }
            });

            c3.generate({
                bindto: '#pie',
                data:{
                    columns: [
                        ['Progress1', 30],
                        ['Progress2', 120]
                    ],
                    colors:{
                        Progress1: '#1ab394',
                        Progress2: '#BABABA'
                    },
                    type : 'pie'
                }
            });//Dashboard

			c3.generate({
				bindto: '#tuv',
				size: {
        			height: 212,
    			},
			    data: {
			        columns: [
			        	['Visitors', 40, 15, 40, 53, 32, 32, 0],
			            //['data2', 20, 40, 35, 16, 70, 18, 75]
			            
			        ],
			        types: {
			            Visitors: 'area-spline',
			            Visitors: 'area-spline'
			        },
			        colors: {
			        	Visitors: "#999",
			        	Visitors: "#1ab394"
			        }
			    }
			});
			c3.generate({
				bindto: '#ldgen',
				size: {
        			height: 220,
    			},
			    data: {
			        columns: [
			            ['Leads', 30, 200, 100, 300, 150, 250, 30, 200, 100, 350, 150, 250,30, 200, 100, 250, 150, 250,30, 200, 100, 310, 150, 250,30, 200, 100, 280, 150, 250]
			        ],
			        type: 'bar',
			        colors:{Leads: "#1ab394"}
			    },
			    bar: {
			        width: {
			            ratio: 0.9 // this makes bar width 50% of length between ticks
			        }

			    }
			});
			c3.generate({
				bindto: '#cugen',
				size: {
        			height: 220,
    			},
			    data: {
			        columns: [
			        	['Cutomers', 4, 8, 5, 10, 3, 16, 6, 12, 8, 9, 30, 11, 13, 4, 6, 3, 7]		            
			        ],
			        types: {
			            Cutomers: 'area-spline'
			        },
			        colors: {
			        	Cutomers: "#1ab394"
			        }
			    }
			});
			c3.generate({
                bindto: '#top5ref',
                data:{
                    columns: [
                        ['Facebook', 25],
                        ['Linkedin', 10],
                        ['Twitter', 30],
                        ['Pinterest', 20],
                        ['Google', 15]
                    ],
                    colors:{
                        Facebook: '#1ab394',
                        Twitter: '#BABABA'
                    },
                    type : 'pie'
                }
            });//Content-detail

			//checkbox
			$('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });


            //Datepicker
            $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

            $('#reportrange').daterangepicker({
                format: 'MM/DD/YYYY',
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2012',
                maxDate: '12/31/2015',
                dateLimit: { days: 60 },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'right',
                drops: 'down',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-primary',
                cancelClass: 'btn-default',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    cancelLabel: 'Cancel',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            });
                
            
			


			//Chosen
            var config = {
                '.chosen-select'           : {},
                '.chosen-select-deselect'  : {allow_single_deselect:true},
                '.chosen-select-no-single' : {disable_search_threshold:10},
                '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chosen-select-width'     : {width:"95%"}
                }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
            resizeChosen();
            $(window).on('resize', resizeChosen);

            //datepicker
            /*$('.daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });*/
            $('.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

    
});

function resizeChosen() {
   $(".chosen-container").each(function() {
       $(this).attr('style', 'width: 100%');
   });          
}