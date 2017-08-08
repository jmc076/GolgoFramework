$( document ).ready(function() {
	 $('.js-lock-screen').click(function(e) {
		 location.href = '/'+ baseHost+ "/lockscreen";
	 })
	 /* ----------==========     Emails Subscription Chart initialization    ==========---------- */
if($('#emailsSubscriptionChart').length) {
	 var dataEmailsSubscriptionChart = {
	   labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
	   series: [
	     [542, 443, 320, 780, 553, 453, 326, 434, 568, 610, 756, 895]

	   ]
	 };
	 var optionsEmailsSubscriptionChart = {
	     axisX: {
	         showGrid: false
	     },
	     low: 0,
	     high: 1000,
	     chartPadding: { top: 0, right: 5, bottom: 0, left: 0}
	 };
	 var responsiveOptions = [
	   ['screen and (max-width: 640px)', {
	     seriesBarDistance: 5,
	     axisX: {
	       labelInterpolationFnc: function (value) {
	         return value[0];
	       }
	     }
	   }]
	 ];
	 var emailsSubscriptionChart = Chartist.Bar('#emailsSubscriptionChart', dataEmailsSubscriptionChart, optionsEmailsSubscriptionChart, responsiveOptions);

	 //start animation for the Emails Subscription Chart
	 gf.startAnimationForBarChart(emailsSubscriptionChart);
}
	
});

 
