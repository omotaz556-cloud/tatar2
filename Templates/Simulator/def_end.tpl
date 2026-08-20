<tbody>
		<tr>
			<th><?php echo TZ_OTHER; ?></th>

		</tr>
		<tr>
			<td class="details">
				<table cellpadding="1" cellspacing="1">
					<tr>
					    <td class="ico"><img src="img/x.gif" class="unit uhab" alt="<?php echo POP; ?>" title="<?php echo POP; ?>" /></td>
					    <td class="desc"><?php echo POP; ?></td>
					    <td class="value"><input class="text" type="text" name="ew2" value="<?php echo $form->getValue('ew2')==""? 1 : $form->getValue('ew2'); ?>" maxlength="4" title="<?php echo TZ_NUMBER; ?> <?php echo POP; ?>" /></td>

					    <td class="research"></td>
				    </tr>
					<tr>
					    <td class="ico"><img src="img/x.gif" class="unit upal" alt="<?php echo TZ_STONEMASON_S_LODGE; ?>" title="<?php echo TZ_STONEMASON_S_LODGE; ?>" /></td>
					    <td class="desc" title="<?php echo TZ_STONEMASON_S_LODGE; ?>"><?php echo TZ_STONEMASON_S_LODGE; ?></td>
					    <td class="value"><input class="text" type="text" name="stonemason" value="<?php echo $form->getValue('stonemason')==""? 0 : $form->getValue('stonemason'); ?>" maxlength="2" title="<?php echo LEVEL; ?> <?php echo TZ_STONEMASON_S_LODGE; ?>" /></td>
					    <td class="research"></td>
				    </tr>
                    <?php
                    if(in_array(1,$target)) {
					if(isset($_POST['wall1']) && $_POST['wall1'] != 0){
					$wall1 = $_POST['wall1'];
					}else{
					$wall1 = 0;
					}
                    echo "<tr>
						    <td class=\"ico\"><img src=\"img/x.gif\" class=\"unit uwall\" alt=\"".CITYWALL."\" title=\"".CITYWALL."\" /></td>
						    <td class=\"desc\">".CITYWALL."</td>
						    <td class=\"value\"><input class=\"text\" type=\"text\" name=\"wall1\" value=\"$wall1\" maxlength=\"2\" title=\"".LEVEL." ".CITYWALL."\" /></td>
						    <td class=\"research\"></td>
				    	</tr>";
                    }
                    if(in_array(2,$target)) {
					if(isset($_POST['wall2']) && $_POST['wall2'] != 0){
					$wall2 = $_POST['wall2'];
					}else{
					$wall2 = 0;
					}
                    echo "<tr>
						    <td class=\"ico\"><img src=\"img/x.gif\" class=\"unit ubarr\" alt=\"".EARTHWALL."\" title=\"".EARTHWALL."\" /></td>

						    <td class=\"desc\">".EARTHWALL."</td>
						    <td class=\"value\"><input class=\"text\" type=\"text\" name=\"wall2\" value=\"$wall2\" maxlength=\"2\" title=\"".LEVEL." ".EARTHWALL."\" /></td>
						    <td class=\"research\"></td>
					    </tr>";
                    }
                    if(in_array(3,$target)) {
					if(isset($_POST['wall3']) && $_POST['wall3'] != 0){
						$wall3 = $_POST['wall3'];
					}else{
					$wall3 = 0;
					}
                    echo "<tr>
						    <td class=\"ico\"><img src=\"img/x.gif\" class=\"unit ustock\" alt=\"".PALISADE."\" title=\"".PALISADE."\" /></td>
						    <td class=\"desc\">".PALISADE."</td>
						    <td class=\"value\"><input class=\"text\" type=\"text\" name=\"wall3\" value=\"$wall3\" maxlength=\"2\" title=\"".LEVEL." ".PALISADE."\" /></td>
						    <td class=\"research\"></td>

					    </tr>";
                    }
                    if(in_array(6,$target)) {
					if(isset($_POST['wall6']) && $_POST['wall6'] != 0){
					$wall6 = $_POST['wall6'];
					}else{
					$wall6 = 0;
					}
                    echo "<tr>
						    <td class=\"ico\"><img src=\"img/x.gif\" class=\"unit uwall\" alt=\"".MAKESHIFTWALL."\" title=\"".MAKESHIFTWALL."\" /></td>
						    <td class=\"desc\">".MAKESHIFTWALL."</td>
						    <td class=\"value\"><input class=\"text\" type=\"text\" name=\"wall6\" value=\"$wall6\" maxlength=\"2\" title=\"".LEVEL." ".MAKESHIFTWALL."\" /></td>
						    <td class=\"research\"></td>
				    	</tr>";
                    }
                    if(in_array(7,$target)) {
					if(isset($_POST['wall7']) && $_POST['wall7'] != 0){
					$wall7 = $_POST['wall7'];
					}else{
					$wall7 = 0;
					}
                    echo "<tr>
						    <td class=\"ico\"><img src=\"img/x.gif\" class=\"unit uwall\" alt=\"".STONEWALL."\" title=\"".STONEWALL."\" /></td>
						    <td class=\"desc\">".STONEWALL."</td>
						    <td class=\"value\"><input class=\"text\" type=\"text\" name=\"wall7\" value=\"$wall7\" maxlength=\"2\" title=\"".LEVEL." ".STONEWALL."\" /></td>
						    <td class=\"research\"></td>
				    	</tr>";
                    }
                    if(in_array(8,$target)) {
					if(isset($_POST['wall8']) && $_POST['wall8'] != 0){
					$wall8 = $_POST['wall8'];
					}else{
					$wall8 = 0;
					}
                    echo "<tr>
						    <td class=\"ico\"><img src=\"img/x.gif\" class=\"unit uwall\" alt=\"".DEFENSIVEWALL."\" title=\"".DEFENSIVEWALL."\" /></td>
						    <td class=\"desc\">".DEFENSIVEWALL."</td>
						    <td class=\"value\"><input class=\"text\" type=\"text\" name=\"wall8\" value=\"$wall8\" maxlength=\"2\" title=\"".LEVEL." ".DEFENSIVEWALL."\" /></td>
						    <td class=\"research\"></td>
				    	</tr>";
                    }
                    if(in_array(9,$target)) {
					if(isset($_POST['wall9']) && $_POST['wall9'] != 0){
					$wall9 = $_POST['wall9'];
					}else{
					$wall9 = 0;
					}
                    echo "<tr>
						    <td class=\"ico\"><img src=\"img/x.gif\" class=\"unit uwall\" alt=\"".BARRICADE."\" title=\"".BARRICADE."\" /></td>
						    <td class=\"desc\">".BARRICADE."</td>
						    <td class=\"value\"><input class=\"text\" type=\"text\" name=\"wall9\" value=\"$wall9\" maxlength=\"2\" title=\"".LEVEL." ".BARRICADE."\" /></td>
						    <td class=\"research\"></td>
				    	</tr>";
                    }
                    ?>
                        <tr>
					    <td class="ico"><img src="img/x.gif" class="unit upal" alt="<?php echo PALACE; ?>" title="<?php echo PALACE; ?>" /></td>
					    <td class="desc" title="<?php echo TZ_PALACE_RESIDENCE; ?>"><?php echo PALACE; ?></td>
					    <td class="value"><input class="text" type="text" name="palast" value="<?php echo $form->getValue('palast')==""? 0 : $form->getValue('palast'); ?>" maxlength="2" title="<?php echo LEVEL; ?> <?php echo PALACE; ?>" /></td>
					    <td class="research"></td>
				    </tr>
				    <tr>
						<td class="ico"><img src="img/x.gif" class="unit uhero" title="<?php echo U0; ?>" alt="<?php echo U0; ?>" /></td>
						<td class="desc"><?php echo TZ_HERO_DEF_BONUS; ?></td>
						<td class="value"><input class="text" type="text" name="h_def_bonus" value="<?php echo $form->getValue('h_def_bonus')==""? 0 : $form->getValue('h_def_bonus'); ?>" maxlength="4" title="<?php echo TZ_HERO_DEF_BONUS; ?>" /></td>
						<td class="research"></td>
				    </tr>
					
					
				</table>
			</td>
		</tr></tbody></table>
