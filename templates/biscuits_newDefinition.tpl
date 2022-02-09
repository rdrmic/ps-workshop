<div id="item-detail" style="width: 850px;">
    <form method="post">
        <input type="hidden" id="biscuit-id" value="[@id]" />
        <input type="hidden" id="selection-materials" value="[@materials]" />
        <input type="hidden" id="codes-in-use" value="[@codes-in-use]" />

        <ul class="form-style-1">
            <li>
                <label>Šifra</label>
                <input type="text" name="code" value="[@code]" class="field-middle ctrl-required ctrl-unique-code" />
            </li>
            <li>
                <label>Naziv</label>
                <input type="text" name="name" value="[@name]" class="field-long ctrl-required" />
            </li>
            <li>
                <label><span class="not-required">*</span>Napomena</label>
                <textarea cols="35" rows="3" name="note" class="field-textarea">[@note]</textarea>
                <br /><br />
            </li>
            
            <li>
                <label id="label-work_price"><span class="not-required">*</span>Cijena rada po biskvitu</label>
                <input type="text" name="work_price" id="work_price" value="[@work_price]" placeholder="0,00" class="field-middle right-align ctrl-currency" /> Kn
                <br /><br />
            </li>
            
            <li>
                <label id="label-materials">Materijali</label>
                <div id="materials-selections" class="building-items-selections"><!-- JS-generated select elements --></div>
                <button type="button" class="button-orange" onclick="addBuildingItemSelection(this.form, 'materials');">+</button>
                
                <!-- read-only -->
                <div style="margin-top: 3px;">
                    <input type="text" name="materials_price" id="materials_price" value="[@materials_price]"
                        placeholder="0,00" class="field-middle right-align readonly" readonly="readonly"
                    /><span class="readonly"> Kn</span>
                </div>
                <br />
            </li>
            
            <li>
                <!-- read-only -->
                <label class="readonly">Ukupna cijena biskvita</label>
                <input type="text" name="price" id="price" value="[@price]" placeholder="0,00" class="field-middle right-align readonly" readonly="readonly" /><span class="readonly"> Kn</span>
            </li>
            
            <li class="err_msg_to_user">[@msg]</li>
            
            <li class="button-bar">
                <button type="button" class="button-orange button-orange-calculate" onclick="calculateDefinition(this.form, 'biscuits');">Izračunaj</button>
                
                <input type="submit" id="submit" name="submit" value="Spremi" disabled="disabled" class="disabled" />
                <button type="button" class="button-orange" onclick="document.location='?biscuits';">Odustani</button>
            </li>
        </ul>
    </form>
</div>
