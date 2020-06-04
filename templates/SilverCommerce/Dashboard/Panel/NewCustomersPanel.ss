<div class="dashboard-commerce-panel dashboard-new-customers">
    <table>
        <thead>
            <tr>
                <th><%t SilverCommerce.Name "Name" %></th>
                <th><%t SilverCommerce.Joined "Joined" %></th>
            </tr>
        </thead>
        <tbody>
            <% loop Customers %>
                <tr>
                    <td>$FirstName $Surname</td>
                    <td>$Created.Nice</td>
                <tr>
            <% end_loop %>
        </tbody>
    </table>
</div>