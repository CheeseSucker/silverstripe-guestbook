<% include SideBar %>
<% require css(guestbook/css/guestbook.css) %>
<% require javascript(guestbook/javascript/guestbook.js) %>

<div class="guestbook">
	<h1>$Title</h1>

	$Content

	<% if PaginatedEntries.Count == 0 %>
		<p class="no-entries-message">Be the first to sign this guestbook!</p>
	<% end_if %>

	<% loop $PaginatedEntries %>
		<div class="entry">
			<div class="actions">
				<% if $Email %>
					<a href="$EmailURL" class="action email">E-mail</a>
				<% end_if %>
				<% if $Website %>
					<a href="$Website" rel="popup" class="action website">
						Website
					</a>
				<% end_if %>
				<% if $Top.Moderator %>
					<a href='$EditLink' title="Edit" class="action edit">Edit</a>
					<a href='$DeleteLink' onclick="return confirm('Are you sure you want to delete this entry?')"
					   title="Delete" class="action delete">Delete</a>
				<% end_if %>
			</div>
			<div class="title">$Name</div>
			<div class="date">$Date</div>
			<div class="message">
				$FormattedMessage

			<% if $Comment %>
				<div class="comment">
					<strong>Comment [Administrator]:</strong><br />
					$FormattedComment
				</div>
			<% end_if %>
			</div>
		</div>
	<% end_loop %>	

	<p class="pagination">
		<% with PaginatedEntries %>
			<% include PageControls %>
		<% end_with %>
	</p>

	<p>Entries: <strong>$PaginatedEntries.Count</strong></p>

	<h2>New entry</h2>
	$NewEntryForm
</div>
