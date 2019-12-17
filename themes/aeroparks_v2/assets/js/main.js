$(function () {


	//Instagram Feed
	var feed = new Instafeed({
		get: 'user',
		userId: '10665736023',
		accessToken: '10665736023.598e3fc.ff4e6c12d9bb466e9c6978a796e8cc4f',
		resolution: 'thumbnail',
		template: '<a class="grid-item" href="{{link}}" target="_blank"><img src="{{image}}" /></a>',
		sortBy: 'most-recent',
		limit: 4,
		links: false
	});
	feed.run();



});