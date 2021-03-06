<div  class="menu js-top-menu position-static">
<ul class="top-menu" data-child="1">
    {foreach from=$items item=item}
        <li class="menu-item">
            {if !$item.is_single}
                <span class="menu-item-title dropdown-item">{$item.name_item}</span>
                <div class="menu-blocks popover sub-menu js-sub-menu collapse">
                {foreach from=$item.list_block item=block}
                    <div class="menu-block">
                        <span class="menu-block-name">{$block.block.name_block}</span>
                            {if sizeof($block.block.children)!=0}
                                {foreach from=$block.block.children item=block}
                                    <div class="menu-block-child">
                                        <span class="menu-block-child-name">-{$block.name_block}-</span>
                                        <ul class="menu-block-child-link">
                                            {foreach from=$block.list_link item=link}
                                                {if $link.link.type=='custom'}
                                                    <i><span  data-o="{base64_encode($link.link.url)} class="obflink {if isset($link.link.current)}{$link.link.current}{/if}">{$link.link.libelle_link}</span></i>
                                                {else}
                                                    <i><a href="{$link.link.url} class="menulink {if isset($link.link.current)}{$link.link.current}{/if}">{$link.link.libelle_link}</a></i>
                                                {/if}

                                            {/foreach}
                                        </ul>
                                    </div>
                                {/foreach}
                            {else}
                                <ul class="menu-block-link">
                                    {foreach from=$block.block.list_link item=link}
                                        <i><a href="{$link.link.url}" class="menulink {if isset($link.link.current)}{$link.link.current}{/if}">{$link.link.libelle_link}</a></i>
                                    {/foreach}
                                </ul>
                            {/if}
                    </div>
                {/foreach}
                </div>
            {else}
                <span class="menu-item-title"><a href="{$item.link.url}" class="menulink {if isset($item.link.current)}{$item.link.current}{/if}">{$item.name_item}</a></span>
            {/if}
        </li>
    {/foreach}
</ul>
</div>