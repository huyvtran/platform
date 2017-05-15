/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.protectedSource.push(/<\?[\s\S]*?\?>/g);
    config.font_defaultLabel = 'Arial';
    config.fontSize_defaultLabel = '12px';
	config.allowedContent = true;
	config.extraPlugins = "sourcedialog";
    config.smiley_images = [
        'airplane.png','alien.png','angel.png','angry.png','announce.png','arrogant.png','bashful.png','beat-up.png','beauty.png','beer.png', 'blush.png','bomb.png',
        'bowl.png','brb.png','bug-eyes.png','bunny.png','bye.png','cake.png','call-me.png','camera.png','can.png','car.png','cat.png','cat2.png','chic.png','chick.png',
        'chicken.png','chicken2.png','cigarette.png','clap.png','clock.png','cloudy.png','clover.png','clown.png','coffee.png','coins.png','computer.png','confused.png',
        'console.png','cool.png','cow.png','cow2.png','cowboy.png','crying.png','curl-lip.png','curse.png','cute.png','cyclops.png','dance.png','dazed.png','devil.png',
        'disdain.png','doctor.png','dog.png','dog2.png','doh.png','dont-know.png','drink.png','drool.png','duck.png','eat.png','evil-grin.png','eyeroll.png','female.png',
        'fighter-f.png','fighter-m.png','film.png','fingers-xd.png','flag-us.png','foot-in-mouth.png','frown.png','frown-big.png','ghost.png','giggle.png','goat.png',
        'go-away.png','grin.png','hammer.png','handcuffs.png','handshake.png','heart.png','heart-broken.png','highfive.png','hippo.png','hug-left.png','hug-right.png',
        'hypnotized.png','in-love.png','island.png','jump.png','kiss.png','kiss-blow.png','kissed.png','kissing.png','knife.png','koala.png','lamp.png','lashes.png',
        'laugh.png','lion.png','liquor.png','loser.png','lying.png','mail.png','male.png','mean.png','meeting.png','mobile.png','mohawk.png','moneymouth.png','monkey.png',
        'monkey2.png','moon.png','mouse.png','music.png','music-note.png','nailbiting.png','nerd.png','neutral.png','on-the-phone.png','pain.png','panda.png','party.png',
        'peace.png','phone.png','pig.png','pig2.png','pill.png','pirate.png','pissed-off.png','pizza.png','plate.png','poop.png','pray.png','present.png','pumpkin.png',
        'question.png','quiet.png','rain.png','rainbow.png','razz.png','razz-drunk.png','razz-mad.png','really-angry.png','really-pissed.png','reindeer.png','rose.png',
        'rose-dead.png','rotfl.png','sarcastic.png','search.png','secret.png','shame.png','sheep.png','sheep2.png','shock.png','shout.png','shut-mouth.png','sick.png',
        'sidefrown.png','silly.png','skeleton.png','skywalker.png','sleepy.png','smile.png','smile-big.png','smirk.png','snail.png','snicker.png','snowman.png',
        'soccerball.png','soldier.png','star.png','starving.png','stop.png','struggle.png','sun.png','sweat.png','talktohand.png','teeth.png','thinking.png',
        'thumbs-down.png','thumbs-up.png','thunder.png','tiger.png','time-out.png','tremble.png','turtle.png','tv.png','umbrella.png','vampire.png','victory.png',
        'waiting.png','watermelon.png','waving.png','weep.png','wilt.png','wink.png','worship.png','yawn.png','yin-yang.png','zombie-killer.png',
    ];
    config.specialChars = [
        '!', '&quot;', '#', '$', '%', '&amp;', "'", '(', ')', '*', '+', '-', '.', '/',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';',
        '&lt;', '=', '&gt;', '?', '@',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        '[', ']', '^', '_', '`',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
        'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        '{', '|', '}', '~',
        '&euro;', '&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;', '&ndash;', '&mdash;', '&iexcl;', '&cent;', '&pound;',
        '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&reg;', '&macr;',
        '&deg;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;',
        '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;',
        '&AElig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;',
        '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;',
        '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;',
        '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;', '&ecirc;', '&euml;', '&igrave;', '&iacute;',
        '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;',
        '&oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;', '&OElig;', '&oelig;',
        '&#372;', '&#374', '&#373', '&#375;', '&sbquo;', '&#8219;', '&bdquo;', '&hellip;', '&trade;', '&#9658;', '&bull;',
        '&rarr;', '&rArr;', '&hArr;', '&diams;', '&asymp;','🏁','🏆','🌸','🌈','⚡','☀','💰','🎌','🎅','🎄','🎁','🎉','❤',
        '💎','🌟','🔥','✨','👑',
    ];
	config.forcePasteAsPlainText = true;
};