{extends file="parent:widgets/emotion/components/component_html.tpl"}

{block name="widget_emotion_component_html_title"}
    {if $Data.cms_title && ($Data.simkl_headline|in_array:['h1','h2','h3','h4','h5','h6'])}
        <{$Data.simkl_headline} class="html--title{if !$Data.needsNoStyling} panel--title is--underline{/if}">
            {$Data.cms_title}
        </{$Data.simkl_headline}>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}