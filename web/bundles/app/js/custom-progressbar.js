(function($) {
	class ProfessionalProgressBar {
		constructor(container, progressBarData, settings) {
			this.container = container;
			this.settings = JSON.parse(settings.replace(/&quot;/g,'"'));;
			this.elementTagName = 'li';
			this.data = JSON.parse(progressBarData.replace(/&quot;/g,'"'));
			this.init();
		}	
		init() {
			this.createProgressBarElements();
		}

		createProgressBarElements() {
			let before = '';
			let class_name = '';

			for(let i = 0; i < this.data.length; i++) {

				let value = this.data[i]; 
				if(i == 0) {
					class_name = 'visited first';
				} else {
					before = class_name; 
					class_name = 'visited';
				}

				if(value[0] == '@') {
					class_name = 'active';
				}

				if(class_name == 'next') {
					class_name = '';
				}

				if(before == 'active') {
					class_name = 'next';
				}

				if(before == 'next') {
					class_name = '';
				}

				this.createElement(this.elementTagName, class_name);
			}
		}

		createElement(tag, class_name) {
			let element = $("<" + tag + "></" + tag + ">");
			element.addClass(class_name);
			element.css({width: this.settings.element.width});
			element.text();
		
			$(this.container).append(element);
		}
	}

	$( document ).ready(function() {
		if(progressBarData != undefined)
	    new ProfessionalProgressBar($('#my-progressbar'), progressBarData, settings);
	});

})(jQuery); 