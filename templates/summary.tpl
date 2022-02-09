<div id="item-list-header">
    <form method="post">
        <input type="submit" name="delete_all_data" class="button-red button-app-reset" value="Resetiranje podataka"
                onclick="return confirm('Potrebno potvrditi');" />
    </form>
</div>
<div class="item-list item-list-summary">
    <table>
        <thead>
            <th>SKLADIŠTE</th>
            <th class="table_right_align">VRIJEDNOST</th>
        </thead>
        <tfoot>
            <tr class="table-summary-tr">
                <td class="table-summary-td" style="border-right: 1px solid #bbb;"></td>
                <td class="table-summary-td-ok table_right_align">[@sum-all] Kn</td>
            </tr>
        </tfoot>
        <tbody>
            <tr class="countable-rows">
                <td>Materijali</td>
                <td class="table_right_align">[@sum-materials] Kn</td>
            </tr>
            <tr class="countable-rows">
                <td>Biskviti</td>
                <td class="table_right_align">[@sum-biscuits] Kn</td>
            </tr>
            <tr class="countable-rows">
                <td>Poluproizvodi</td>
                <td class="table_right_align">[@sum-incompletes] Kn</td>
            </tr>
            <tr class="countable-rows">
                <td>Gotovi proizvodi</td>
                <td class="table_right_align">[@sum-products] Kn</td>
            </tr>
        </tbody>
    </table>
</div>
