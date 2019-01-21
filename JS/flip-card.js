var init = function() {
    var desArray = {
        'Tap': 'This symbol means "tap this card." It appears only as a cost to activate an ability.',
        'X': 'Some spells and abilities have effects that change depending on how much mana you use to pay for them.',
        'Cast': 'You cast a spell by paying its mana cost and putting it onto the stack.',
        'Control': 'You control the creatures and other permanents that you have on the battlefield, unless your opponent uses a spell or ability to gain control of one of your permanents.',
        'Counter a spell': 'If a card counters a spell, you can cast it in response to a spell your opponent is casting. The countered spell has no effect, and it’s put into the graveyard.',
        'Counter on a card': 'Sometimes counters are put on a card to keep track of something. Many are +1/+1 counters, which each give a creature +1 power and +1 toughness. You can use any small item to represent counters.',
        'Damage': 'Creatures deal damage equal to their power during combat. Spells can also deal damage to creatures and players.',
        'Deathtouch': 'A creature dealt damage by a creature with deathtouch is destroyed.',
        'Defender': 'A creature with defender can’t attack.',
        'Destroy': 'A permanent that’s destroyed is put into the graveyard. Creatures that are dealt damage at least equal to their toughness in a single turn are destroyed. Spells and abilities can also destroy permanents.',
        'Dies': 'Another way to say a creature has been put into a graveyard from the battlefield.',
        'Discard': 'To discard a card, choose a card from your hand and put it into your graveyard.',
        'Double strike': 'A creature with double strike deals damage twice each combat: once before creatures without first strike or double strike, and then again when creatures normally deal damage.',
        'Enchant': 'An Aura is an enchantment that enchants (attaches to) another card on the battlefield. If that creature leaves the battlefield, the Aura is put into the graveyard.',
        'Equip': 'If you have an Equipment card on the battlefield, you can pay its equip cost to attach it to one of your creatures on the battlefield. If the equipped creature leaves the battlefield, the Equipment card stays.',
        'Exile': 'If an ability exiles a card, it’s removed from the battlefield and set aside. An exiled card isn’t a permanent and isn’t in the graveyard.',
        'Fight': 'When two creatures fight, each deals damage equal to its power to the other. This is different from creatures dealing damage in combat.',
        'First strike': 'A creature with first strike deals its damage in combat before creatures without first strike or double strike.',
        'Flash': 'You may cast a spell with flash any time you could cast an instant, even in response to other spells.',
        'Flying': 'A creature with flying can be blocked only by other creatures with flying and creatures with reach.',
        'Haste': 'A creature with haste can attack and you can activate its oT abilities as soon as it comes under your control.',
        'Hexproof': 'A creature with hexproof can’t be the target of spells or abilities your opponents control, including Aura spells. Your spells and abilities can still target it.',
        'Indestructible': 'An indestructible permanent can’t be destroyed by damage or by effects that say “destroy.” It can still be sacrificed or exiled.',
        'Intimidate': 'A creature with intimidate can’t be blocked except by artifact creatures and/or creatures that share a color with it.',
        'Lifelink': 'If a creature with lifelink deals damage, its controller also gains that much life.',
        'Mana': 'This is the energy you get from your lands that you use to cast spells. Mana can be white, blue, black, red, green, and sometimes even colorless.',
        'Next damage': 'Sometimes an ability refers to the “next time” something happens or the “next damage” that a creature or player would be dealt. Remember that an instant or ability used in response to something happens first.',
        'Permanent': 'Lands, creatures, artifacts, enchantments, and planeswalkers are permanents. They enter the battlefield after you cast them. Token creatures are also permanents.',
        'Protection': 'A creature with protection from a color can’t be blocked, dealt damage, enchanted, or targeted by anything of that color.',
        'Reach': 'A creature with reach can block creatures with flying (and creatures without flying).',
        'Regenerate': 'Regenerating a creature keeps it from being destroyed. Instead of being destroyed, the creature gets tapped, it’s removed from combat (if it’s in combat), and all its damage is healed.',
        'Sacrifice': 'Sometimes a card tells you to sacrifice a creature or some other permanent. To sacrifice a permanent, you move it from the battlefield to your graveyard. You can’t regenerate it or save it in any way.',
        'Target': 'If a spell uses the word “target,” you choose what the spell will affect when you cast it. The same is true for abilities you activate.',
        'Token': 'Some cards create token creatures. You can use token cards from booster packs, glass beads, dice, or anything else to represent them.',
        'Trample': 'If a creature with trample would assign enough damage to its blockers to destroy them, you may have it assign the rest of its damage to the player or planeswalker it’s attacking.',
        'Untap': 'Untap a tapped card by turning it right side up. When you untap your permanents at the beginning of your turn, it means that you can use (tap) them again.',
        'Vigilance': 'A creature with vigilance doesn’t tap to attack. (Vigilance doesn’t allow a tapped creature or a creature that entered the battlefield this turn to attack, though.)'
    };
    
    var flippers = document.getElementsByClassName('flip');
    var flipCard = function() {
        var word = document.getElementById('word');
        var description = document.getElementById('description');
        var card = document.getElementById('card');
        
        word.innerHTML = this.title;
        description.innerHTML = desArray[this.title];
        card.toggleClassName('flipped');
    };

    for (var i = 0; i < flippers.length; i++) {
        flippers[i].addEventListener( 'mouseover', flipCard);
        flippers[i].addEventListener( 'mouseout', flipCard);
    };
};

window.addEventListener('DOMContentLoaded', init, false);