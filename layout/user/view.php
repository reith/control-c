<div id="userContainer" class="span12"></div>

<script type="text/javascript">
require(['backbone', 'lib/model/user', 'lib/view/userProfile'], 
function(Backbone, User, UserView) {
	var PageApp = Backbone.View.extend({
		defaults: {
			user: null,
			userProfileView: null
		},

		initialize: function( userData ) {
			this.user = new User(userData);
			this.userProfileView = new UserView({model: this.user});
		},

		render: function() {
			this.userProfileView.render();
			$('#userContainer').html(this.userProfileView.$el);
		}

	});

	var page = new PageApp(<?= json_encode($t['user']) ?>);
	page.render();
});
</script>
