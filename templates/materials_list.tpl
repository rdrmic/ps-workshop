[@item_list_header]
<div class="item-list">
    <table>
        <thead>
            <th>ŠIFRA</th>
            <th>NAZIV</th>
            <th class="table_right_align">PAKIRANJE</th>
            <th class="table_right_align">CIJENA</th>
            <th>NAPOMENA</th>
            <th class="table_right_align">KOLIČINA</th>
            <th class="table_right_align">VRIJEDNOST</th>
            <!--
            <th></th>
            <th></th>
            <th></th>
            -->
        </thead>
        <tfoot>
            <tr class="table-summary-tr">
                <td class="table-summary-td"></td>
                <td class="table-summary-td"></td>
                <td class="table-summary-td"></td>
                <td class="table-summary-td"></td>
                <td class="table-summary-td"></td>
                <td class="table-summary-td" style="border-right: 1px solid #bbb;"></td>
                <td class="table-summary-td-ok table_right_align">[@storage-money-sum] Kn</td>
            </tr>
        </tfoot>
        <tbody>
            [@item-list]
        </tbody>
    </table>
</div>
