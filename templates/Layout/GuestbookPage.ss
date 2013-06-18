<!-- TODO: E-mail address obfuscation -->
<% include SideBar %>
<% require css(mod_guestbook/css/style.css) %>
<% require javascript(mod_guestbook/javascript/guestbook.js) %>



<div id="gjestebok">
	<h1>$Title</h1>

	$Content

	<% loop $PaginatedPages %>
		<div class="innlegg">
			<div class="moderator portal-guestbook-actions">
				<% if $Email %>
					<a href="mailto:$Email" class="portal-guestbook-email">E-mail</a>
				<% end_if %>
				<% if $Website %>
					<a href="$Website" rel="popup" class="portal-guestbook-website">
						Website
					</a>
				<% end_if %>
				<% if $Top.Moderator %>
					<a href='$EditLink' title="Edit" class="portal-guestbook-edit">Edit</a>
					<a href='$DeleteLink' onclick="return confirm('Are you sure you want to delete this entry?')"
					   title="Delete" class="portal-guestbook-delete">Delete</a>
				<% end_if %>
			</div>
			<div class="tittel">$Name</div>
			<div class="dato">$Date</div>
			<div class="tekst">
				$FormattedMessage

			<% if $Comment %>
				<div class="kommentar">
					<strong>Kommentar [Administrator]:</strong><br />
					$FormattedComment
				</div>
			<% end_if %>
			</div>
		</div>
	<% end_loop %>


	<p id="sider_bunn">
		<% with PaginatedPages %>
			<% include PageControls %>
		<% end_with %>
	</p>
	<p>Antall innlegg: <strong>$Entries.Count</strong></p>

	<h2>Nytt innlegg</h2>
	$NewEntryForm
</div>
