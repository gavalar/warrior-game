function Warrior()
{
    this.start = function()
    {
        $('#add_more').mouseup(
            function(e)
            {
                if($('select').length = 9)
                {
                    var id = $('select').length + 1;
                    
                    $('#add_more').before('<div><strong class="player' + $('select').length + '">Player ' + id + ':</strong> <input type="text" id="name-' + id + '" name="name[' + id + ']" value="" /><select id="warrior-' + id + '" name="warrior[' + id + ']"><option value="x">Random</option><option value="1">Ninja</option><option value="2">Samurai</option><option value="3">Brawler</option></select></div>');
                }
                else
                {
                    if($('#warning').length > 0)
                    {
                        $('#warning').remove();
                    }

                    $('#special').before('<div id="warning">There is a maximum limit of 9 warriors per match</div>');
                    var warning = $('#warning');
                }
            }
        );
    }

    this.highlightWarriors = function(warriors)
    {
        var highlight = $('li strong');

        highlight.each(function(index){
            for(key in warriors)
            {
                if($(this).text() == warriors[key])
                { 
                    $(this).attr('class','player' + key);
                }
            }
        });
    }
}

var Warrior = new Warrior();

$(document).ready(function(){
    Warrior.start();
});
