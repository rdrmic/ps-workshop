<div id="item-detail" style="width: 500px;">
    <form method="post">
        <input type="hidden" name="id" id="material-id" value="[@id]" />
        <input type="hidden" id="codes-in-use" value="[@codes-in-use]" />

        <ul class="form-style-1">
            <li>
                <label id="label-code">Šifra</label>
                <input type="text" name="code" id="input-code" value="[@code]" class="field-middle ctrl-required ctrl-unique-code" />
            </li>
            <li>
                <label id="label-name">Naziv</label>
                <input type="text" name="name" id="input-name" value="[@name]" class="field-long ctrl-required" />
            </li>
            <li>
                <label><span class="not-required">*</span>Napomena</label>
                <textarea cols="35" rows="3" name="note" class="field-textarea">[@note]</textarea>
                <br /><br />
            </li>
            
            <li>
                <label id="label-packaging">Pakiranje (količina i mjerna jedinica)</label>
                <input
                    type="text"
                    name="package_quantity"
                    id="input-package_quantity"
                    value="[@package_quantity]"
                    placeholder="0"
                    class="field-middle right-align ctrl-int-gt-zero-required"
                />
                <input
                    type="text"
                    name="package_measure_unit"
                    id="input-package_measure_unit"
                    value="[@package_measure_unit]"
                    class="field-short ctrl-alphabetic-required"
                />
            </li>
            <li>
                <label id="label-price"><span class="not-required">*</span>Cijena po pakiranju</label>
                <input
                    type="text"
                    name="price"
                    id="input-price"
                    value="[@price]"
                    placeholder="0,00"
                    class="field-middle right-align ctrl-currency"
                /> Kn
            </li>
            
            <li class="err_msg_to_user">[@msg]</li>
            
            <li class="button-bar">
                <input type="submit" name="submit" value="Spremi" onclick="return checkItemDefinitionInputValues('materials');" />
                <button type="button" class="button-orange" onclick="document.location='?materials';">Odustani</button>
            </li>
        </ul>
    </form>
</div>
