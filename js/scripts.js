var Businesses = {
	init: function(config) {
		this.config = config;
		this.setupTemplates();
		this.bindEvents();
		config.submitBtn.remove();
		config.businessSearch.focus();
		$.ajaxSetup({
			url: 'index.php',
			type: 'POST',
			dataType: 'JSON'
		});
		this.fetchAllBusinesses();
	},
	
	bindEvents: function() {
		this.config.businessSearch.on('keyup', this.fetchBusinesses);
		this.config.businessList.on('click', 'li', this.displayBusinessDetail);
		this.config.businessDetail.on('click', 'span.close', this.closeOverlay);
	},
	
	setupTemplates: function() {
		this.config.businessListTemplate = Handlebars.compile(this.config.businessListTemplate);
		this.config.businessDetailTemplate = Handlebars.compile(this.config.businessDetailTemplate);
		
		Handlebars.registerHelper('displayBusinessListingPhoto', function( business ) {
			return (business.photo.length) ? business.photo : 'http://dynomob.com/app/images/defaultpic.png';
		});
		
		Handlebars.registerHelper('displayBusinessPhoto', function( business ) {
			return (business.photo.length) ? business.photo : '';
		});
	},
	
	fetchAllBusinesses: function() {
		// this = the select element
		var self = Businesses;
		self.config.businessList.append('<li>Loading...</li>');
		$.ajax({
			data: 'business=',
			success: function(results) {
				self.config.businessList.empty().append((results[0]) ? self.config.businessListTemplate(results) : '');
			}
		});
	},
	
	fetchBusinesses: function() {
		// this = the select element
		var self = Businesses;
		$.ajax({
			data: self.config.form.serialize(),
			success: function(results) {
				self.config.businessList.empty().append((results[0]) ? self.config.businessListTemplate(results) : '<li>No Results. <a href="" target="_self">Try again</a></li>');
			}
		});
	},
	
	displayBusinessDetail: function(e) {
		// this = the list item element
		var self = Businesses;
		var $this = $(this);
		$.ajax({
			data: { business_id: $(this).data('business_id') },
			success: function(results) {
				$this.append(self.config.businessDetail);
				self.config.businessDetail.html((results[0]) ? self.config.businessDetailTemplate(results) : '<div>Error.</div>').slideDown(300);
			}
		});
		
		if ($this.data('business_id')) e.preventDefault();
	},
	
	closeOverlay: function() {
		Businesses.config.businessDetail.slideUp(300).empty();
	}
};

Businesses.init({
	businessSearch: $('#business_search'),
	form: $('#business-selection'),
	businessListTemplate: $('#business_list_template').html(),
	businessDetailTemplate: $('#business_detail_template').html(),
	businessList: $('#business_list'),
	businessDetail: $('.business_detail'),
	submitBtn : $('#submit')
});