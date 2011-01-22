<?php
/**
 * @version		$Id$
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2010 Kunena Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 */
defined ( '_JEXEC' ) or die ();

kimport ( 'kunena.view' );
jimport( 'joomla.cache.handler.output' );

/**
 * Common view
 */
class KunenaViewCommon extends KunenaView {
	public $catid = 0;

	function display($layout = null, $tpl = null) {
		return $this->displayLayout($layout, $tpl);
	}

	function displayDefault($tpl = null) {
		$result = $this->loadTemplate($tpl);
		if (JError::isError($result)) {
			return $result;
		}
		echo $result;
	}

	function displayAnnouncement($tpl = null) {
		if (KunenaFactory::getConfig()->showannouncement > 0) {
			$cache = JFactory::getCache('com_kunena', 'output');
			if ($cache->start(0, 'com_kunena.view.common.announcement')) return;
			// FIXME: refactor code
			require_once(KUNENA_PATH .DS. 'class.kunena.php');
			require_once(KUNENA_PATH_LIB .DS. 'kunena.link.class.php');
			require_once(KUNENA_PATH_LIB .DS. 'kunena.announcement.class.php');
			$ann = new CKunenaAnnouncement();
			$ann->getAnnouncement();
			$ann->displayBox();
			$cache->end();
		} else echo ' ';
	}

	function displayForumJump($tpl = null) {
		$cache = JFactory::getCache('com_kunena', 'output');
		$user = KunenaFactory::getUser ();
		// TODO: we can improve this (not by user)
		if ($cache->start("{$user->userid}.{$this->catid}", 'com_kunena.view.common.forumjump')) return;

		$options = array ();
		$options [] = JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_FORUM_TOP') );
		$cat_params = array ('sections'=>1, 'catid'=>0);
		$this->assignRef ( 'categorylist', JHTML::_('kunenaforum.categorylist', 'catid', 0, $options, $cat_params, 'class="inputbox fbs" size="1" onchange = "this.form.submit()"', 'value', 'text', $this->catid));

		$result = $this->loadTemplate($tpl);
		if (JError::isError($result)) {
			return $result;
		}
		echo $result;
		$cache->end();
	}

	function displayPathway($tpl = null) {
		$cache = JFactory::getCache('com_kunena', 'output');
		$user = KunenaFactory::getUser ();
		// TODO: just testing
		if ($cache->start("{$this->catid}", 'com_kunena.view.common.pathway')) return;

		// FIXME: refactor code
		require_once(KUNENA_PATH .DS. 'class.kunena.php');
		require_once(KUNENA_PATH_LIB .DS. 'kunena.link.class.php');
		$this->config = KunenaFactory::getConfig();
		CKunenaTools::loadTemplate('/pathway.php');
		$cache->end();
	}

	function displayWhosonline($tpl = null) {
		if (KunenaFactory::getConfig()->showwhoisonline > 0) {
			$moderator = KunenaFactory::getUser()->isModerator();
			$cache = JFactory::getCache('com_kunena', 'output');
			if ($cache->start(0, "com_kunena.view.common.whosonline.{$moderator}")) return;
			// FIXME: refactor code
			require_once(KUNENA_PATH .DS. 'class.kunena.php');
			require_once(KUNENA_PATH_LIB .DS. 'kunena.link.class.php');
			require_once (KUNENA_PATH_LIB .DS. 'kunena.who.class.php');
			online = CKunenaWhoIsOnline::getInstance();
			$this->users = 		$online->getActiveUsersList();
			$this->totaluser = 	$online->getTotalRegistredUsers ();
			$this->totalguests = 	$online->getTotalGuestUsers ();
			$this->who_name = 	$online->getTitleWho ($totaluser, $totalguests);
			$result = $this->loadTemplate($tpl);
			if (JError::isError($result)) {
				return $result;
			}
			echo $result;
			$cache->end();
		} else echo " ";
	}

	function displayStats($tpl = null) {
		if (KunenaFactory::getConfig()->showstats > 0) {
			$cache = JFactory::getCache('com_kunena', 'output');
			if ($cache->start(0, 'com_kunena.view.common.stats')) return;
			// FIXME: refactor code
			require_once(KUNENA_PATH .DS. 'class.kunena.php');
			require_once(KUNENA_PATH_LIB .DS. 'kunena.link.class.php');
			require_once(KUNENA_PATH_LIB .DS. 'kunena.stats.class.php');
			$kunena_stats = CKunenaStats::getInstance ( );
			$kunena_stats->loadGenStats();
			$kunena_stats->loadLastUser();
			$this->lastestmemberid = $kunena_stats->lastestmemberid;
			$kunena_stats->loadLastDays();
			$this->todayopen = $kunena_stats->todayopen;
			$this->yesterdayopen = $kunena_statsa->yesterdayopen;
			$this->todayanswer = $kunena_stats->todayanswer;
			$this->yesterdayanswer = $kunena_stats->yesterdayanswer;
			$kunena_stats->loadTotalTopics();
			$this->totaltitles = $kunena_stats->totaltitles;
			$this->totalmessages = $kunena_stats->totalmsgs;
			$kunena_stats->loadTotalCategories();
			$this->totalsections = $kunena_stats->totalsections;
			$this->totalcats = $kunena_stats->totalcats;
			$this->userlist1 = CKunenaLink::GetUserlistLink('', $this->totalmembers);
			$this->userlist2 = CKunenaLink::GetUserlistLink('', JText::_('COM_KUNENA_STAT_USERLIST').' &raquo;');
			$result = $this->loadTemplate($tpl);
			if (JError::isError($result)) {
				return $result;
			}
			echo $result;
			$cache->end();
		} else echo " ";
	}

	function displayMenu($tpl = null) {
		// Menu module has already caching in it
		$menu = KunenaRoute::getMenu ();
		$key = $menu ? "{$menu->id}.{$menu->name}" : '0';
		$result = $this->loadTemplate($tpl);
		if (JError::isError($result)) {
			return $result;
		}
		echo $result;
	}

	function displayLoginBox($tpl = null) {
		$my = JFactory::getUser ();
		$cache = JFactory::getCache('com_kunena', 'output');
		$token = JUtility::getToken();
		if ($cache->start("{$my->id}.$token", 'com_kunena.view.common.loginbox')) return;

		$this->assign ( 'me', KunenaFactory::getUser ());

		require_once KPATH_SITE . '/lib/kunena.link.class.php';
		require_once KPATH_SITE . '/lib/kunena.timeformat.class.php';

		$uri = JFactory::getURI ();
		$this->assign ( 'return',  base64_encode ( $uri->toString ( array ('path', 'query', 'fragment' ) ) ) );
		$this->assign ( 'moduleHtml', $this->getModulePosition('kunena_profilebox'));

		$login = KunenaFactory::getLogin();
		if ($my->get ( 'guest' )) {
			$this->setLayout('login');
			if ($login) {
				$this->assignRef ( 'login', $login->getLoginFormFields() );
				$this->assignRef ( 'register', $login->getRegistrationURL() );
				$this->assignRef ( 'lostpassword', $login->getResetURL() );
				$this->assignRef ( 'lostusername', $login->getRemindURL() );
			}
		} else {
			$this->setLayout('logout');
			if ($login) $this->assignRef ( 'logout', $login->getLogoutFormFields() );

			// Private messages
			$private = KunenaFactory::getPrivateMessaging();
			if ($private) {
				$count = $private->getUnreadCount($this->me->userid);
				$this->assign ( 'privateMessages', $private->getInboxLink($count ? JText::sprintf('COM_KUNENA_PMS_INBOX_NEW', $count) : JText::_('COM_KUNENA_PMS_INBOX')));
			}

			// Announcements
			$config = KunenaFactory::getConfig();
			$annmods = @explode ( ',', $config->annmodid );
			if (in_array ( $this->me->userid, $annmods ) || $this->me->isAdmin()) {
				$this->assign ( 'announcements', '<a href="' . CKunenaLink::GetAnnouncementURL ( 'show' ).'">'. JText::_('COM_KUNENA_ANN_ANNOUNCEMENTS').'</a>');
			}

		}
		$result = $this->loadTemplate($tpl);
		if (JError::isError($result)) {
			return $result;
		}
		echo $result;
		$cache->end();
	}

	function displayFooter($tpl = null) {
		require_once KPATH_SITE . '/lib/kunena.link.class.php';
		$catid = 0;
		if (KunenaFactory::getConfig ()->enablerss) {
			if ($catid > 0) {
				kimport ( 'kunena.forum.category.helper' );
				$category = KunenaForumCategoryHelper::get ( $catid );
				if ($category->pub_access == 0 && $category->parent)
					$rss_params = '&catid=' . ( int ) $catid;
			} else {
				$rss_params = '';
			}
			if (isset ( $rss_params )) {
				$document = JFactory::getDocument ();
				$document->addCustomTag ( '<link rel="alternate" type="application/rss+xml" title="' . JText::_ ( 'COM_KUNENA_LISTCAT_RSS' ) . '" href="' . CKunenaLink::GetRSSURL ( $rss_params ) . '" />' );
				$this->assign ( 'rss', CKunenaLink::GetRSSLink ( $this->getIcon ( 'krss', JText::_('COM_KUNENA_LISTCAT_RSS') ), 'follow', $rss_params ));
			}
		}
		$template = KunenaFactory::getTemplate ();
		$credits = CKunenaLink::GetTeamCreditsLink ( $catid, JText::_('COM_KUNENA_POWEREDBY') ) . ' ' . CKunenaLink::GetCreditsLink ();
		if ($template->params->get('templatebyText') !='') {
			$credits .= ' :: <a href ="'. $template->params->get('templatebyLink').'" rel="follow">' . $template->params->get('templatebyText') .' '. $template->params->get('templatebyName') .'</a>';
		}
		$this->assign ( 'credits', $credits );
		$result = $this->loadTemplate($tpl);
		if (JError::isError($result)) {
			return $result;
		}
		echo $result;
	}
}