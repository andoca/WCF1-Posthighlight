<div id="posthighlight" class="hidden">
	<fieldset class="noJavaScript">
		<legend class="noJavaScript">{lang}de.andoca.posthighlight.tabTitle{/lang}</legend>
		

		<div class="formElement">
			<div class="formFieldLabel">
				<label for="posthighlightClass">{lang}de.andoca.posthighlight.selectTitle{/lang}</label>
			</div>
			<div class="formField">
				<select name="posthighlightClass">
					<option value="null"{if $posthighlightClass|isset && $posthighlightClass == 'null'} selected{/if}></option>
					{foreach from=$posthighlightSelect item="class"}
						<option value="{$class.className}"{if $posthighlightClass|isset && $posthighlightClass == $class.className} selected{/if}>{$class.classTitle}</option>
					{/foreach}
				</select>
			</div>
			<div class="formFieldDesc">
				
			</div>
		</div>
		
	</fieldset>
</div>

<script type="text/javascript">
	//<![CDATA[
	tabbedPane.addTab('posthighlight', false);
	//]]>
</script>