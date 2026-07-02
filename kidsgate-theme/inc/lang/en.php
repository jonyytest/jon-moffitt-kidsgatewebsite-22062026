<?php
/**
 * The Kids Gate — English (global) content.
 * Prices in USD. Edit copy here; templates read it via kg_t( 'dot.path' ).
 */

return array(

	'meta' => array(
		'description' => 'The Kids Gate is an AI-powered learning platform for children aged 5–12: Cambridge English and International Maths through games, stories and quizzes. 30-day free trial, no credit card required.',
	),

	'common' => array(
		'skip_to_content' => 'Skip to content',
		'home_aria'       => 'The Kids Gate home',
		'nav_aria'        => 'Main navigation',
		'menu'            => 'Menu',
		'cta_primary'     => 'Start Free Trial',
		'cta_secondary'   => 'See How It Works',
		'store_on'        => 'Download on the',
		'store_get'       => 'Get it on',
		'view_larger'     => 'View larger',
		'zoom_hint'       => 'Tap image to zoom in',
		'zoom_out_hint'   => 'Tap image to zoom out',
		'trust_chips'     => array(
			'30-day free trial',
			'Ages 5–12',
			'Cambridge curriculum',
			array( 'text' => 'No ads. Ever.', 'cross' => true ),
		),
		'cancel_chips'    => array(
			'No credit card required',
			'Cancel at any time',
			'30-day free trial',
		),
		'choose_region'   => 'Choose Region',
	),

	'nav' => array(
		'home'         => 'Home',
		'home_hover'   => 'Return <em>Home</em>',
		'how_it_works' => 'How It Works',
		'features'     => 'Features',
		'parents'      => 'Parents',
		'pricing'      => 'Pricing',
		'schools'      => 'Schools',
		'leaderboard'  => 'Leaderboard',
		'about'        => 'About Us',
		'sponsors'     => 'Sponsors',
		'support'      => 'Support',
	),

	'footer' => array(
		'tagline'                => 'AI-powered learning that feels like play, for children aged 5–12 everywhere.',
		'explore'                => 'Explore',
		'company'                => 'Company',
		'help'                   => 'Need a hand?',
		'support_title'          => 'Visit Support',
		'support_sub'            => 'FAQs & contact form',
		'privacy'                => 'Privacy Policy',
		'terms'                  => 'Terms of Service',
		'coming_soon'            => 'Page coming soon',
		'rights'                 => 'All rights reserved.',
		'email_placeholder_note' => 'Placeholder address. Final support email to be confirmed.',
	),

	/* ---------------------------------------------------------------- Home */
	'home' => array(
		'hero' => array(
			'eyebrow'  => 'Available on iOS &amp; Android',
			'title'    => 'Learning your child<br><span class="kg-squiggle kg-squiggle--draw">actually asks for</span>',
			'lede'     => 'AI-powered Cambridge English and International Maths for ages 5&ndash;12, with games, stories and quizzes that adapt after every single answer.',
			'badge_lessons'  => '1,800+',
			'badge_lessons_label' => 'lessons inside',
			'badge_daily'    => '20 min',
			'badge_daily_label'   => 'a day is enough',
			'img_alt'  => 'A child smiling while learning with The Kids Gate on a tablet',
			'scroll_cue' => 'Step inside',
			'card_mastery'     => 'Mastery up',
			'card_mastery_sub' => 'Fractions &middot; Grade 4',
			'card_tokens'      => '+25 tokens',
			'card_tokens_sub'  => 'Lesson complete!',
			'card_correct'     => 'Correct!',
		),

		'stats' => array(
			'kicker' => 'Why families choose The Kids Gate',
			'title'  => 'Serious learning, disguised as <span class="kg-hl-amber">a great time</span>',
			'lede'   => '',
			'items'  => array(
				array( 'num' => '1800', 'suffix' => '+', 'label' => 'interactive lessons' ),
				array( 'num' => '6',    'suffix' => '', 'label' => 'grade levels, fully covered' ),
				array( 'num' => '20',   'suffix' => ' min', 'label' => 'a day is all it takes' ),
				array( 'num' => '30',   'suffix' => '', 'label' => 'day free trial, no card needed' ),
			),
		),

		'problem' => array(
			'kicker' => 'Sound familiar?',
			'title'  => 'Most learning tools <span class="kg-underline-red">weren\'t</span> built for <em>your</em> child',
			'lede'   => '',
			'items'  => array(
				array(
					'title' => 'Tutoring is expensive',
					'text'  => 'Private tutors charge by the hour, and the costs climb fast, especially with more than one child.',
				),
				array(
					'title' => 'Generic apps don\'t adapt',
					'text'  => 'One-size-fits-all lessons move too fast for some children and too slowly for others.',
				),
				array(
					'title' => 'Repetition kills interest',
					'text'  => 'When learning feels like a chore, children switch off, and progress stalls.',
				),
			),
			'turn_title'    => 'The Kids Gate flips the script',
			'turn_text'     => 'An AI tutor that costs less than a pizza, adjusts to your child after every answer, and makes them <strong>ask</strong> for their daily lesson.',
			'turn_text_in'  => 'An AI tutor that costs less than one tutoring session per month, adjusts to your child after every answer, and makes them <strong>ask</strong> for their daily lesson.',
			'turn_text_ph'  => 'An AI tutor that costs less than your Netflix subscription, adjusts to your child after every answer, and makes them <strong>ask</strong> for their daily lesson.',
			'turn_text_id'  => 'An AI tutor that costs less than a Netflix subscription, adjusts to your child after every answer, and makes them <strong>ask</strong> for their daily lesson.',
		),

		'loop' => array(
			'kicker' => 'How it works',
			'title'  => 'Four steps a day: <span class="kg-squiggle kg-squiggle--draw">each one adapts</span>',
			'lede'   => 'A simple daily loop that compounds into real progress. Try the adaptive engine for yourself.',
			'repeat' => 'Every day, on repeat',
			'steps'  => array(
				array(
					'title' => 'Assessment',
					'text'  => 'A friendly placement quiz finds your child\'s true level in each subject, no stress, no grades.',
				),
				array(
					'title' => 'Personalised path',
					'text'  => 'The AI builds a learning path that fits exactly where your child is, not where the curriculum says they should be.',
				),
				array(
					'title' => 'Daily 20-minute lesson',
					'text'  => 'Games, stories, quizzes, songs and puzzles, short enough to stay fun, long enough to stick.',
				),
				array(
					'title' => 'Rewards & progress',
					'text'  => 'Tokens, badges and a growing world keep motivation high while you watch the progress charts climb.',
				),
			),
			'cta_label' => 'Explore the full journey',
		),

		'experience' => array(
			'kicker' => 'Inside the app',
			'title'  => 'What your child actually sees',
			'lede'   => 'Tap through the tabs, every lesson type is built to feel like play first.',
			'tabs'   => array(
				'english' => array(
					'label'  => 'English',
					'title'  => 'Cambridge English, story by story',
					'text'   => 'Phonics, reading, vocabulary and grammar wrapped in interactive stories and songs.',
					'topics' => array(
						array( 'name' => 'Phonics &amp; Reading', 'desc' => 'Sound out every new word' ),
						array( 'name' => 'Writing &amp; Grammar', 'desc' => 'From letters to whole stories' ),
						array( 'name' => 'Vocabulary', 'desc' => 'New words that actually stick' ),
						array( 'name' => 'Listening', 'desc' => 'Follow along, then respond' ),
						array( 'name' => 'Comprehension', 'desc' => 'Understand, not just decode' ),
						array( 'name' => 'Speaking Confidence', 'desc' => 'Say it out loud, proudly' ),
					),
				),
				'maths' => array(
					'label'  => 'Maths',
					'title'  => 'International Maths that clicks',
					'text'   => 'From counting to fractions, concepts are taught visually, then practised through play.',
					'topics' => array(
						array( 'name' => 'Number &amp; Operations', 'desc' => 'Counting through to calculation' ),
						array( 'name' => 'Geometry', 'desc' => 'Shapes, space and symmetry' ),
						array( 'name' => 'Measurement', 'desc' => 'Length, time, money, weight' ),
						array( 'name' => 'Data &amp; Statistics', 'desc' => 'Reading charts and graphs' ),
						array( 'name' => 'Problem Solving', 'desc' => 'Real-world maths puzzles' ),
						array( 'name' => 'Patterns &amp; Algebra', 'desc' => 'Spot the rule, predict next' ),
					),
				),
				'games' => array(
					'label'  => 'Games & quizzes',
					'title'  => 'The fun is the point',
					'text'   => 'Quizzes, puzzles and mini-games turn practice into something children genuinely look forward to.',
					'topics' => array(
						array( 'name' => 'Quiz Battles', 'desc' => 'Recall under friendly pressure' ),
						array( 'name' => 'Story Adventures', 'desc' => 'Skills woven into the plot' ),
						array( 'name' => 'Memory &amp; Matching', 'desc' => 'Locks in what was just learned' ),
						array( 'name' => 'Speed Drills', 'desc' => 'Fast recall becomes automatic' ),
						array( 'name' => 'Daily Challenges', 'desc' => 'A little practice, every day' ),
						array( 'name' => 'Reward Streaks', 'desc' => 'Consistency earns real tokens' ),
					),
				),
				'rewards' => array(
					'label'  => 'Tokens & rewards',
					'title'  => 'Effort earns real rewards',
					'text'   => 'Learning earns tokens to spend in The Kids Gate Store on avatars, accessories and surprises.',
					'points' => array(
						'Tokens for completed lessons and streaks',
						'A store full of avatar upgrades',
						'Monthly prize draws to enter',
					),
				),
				'world' => array(
					'label'  => 'Virtual world',
					'title'  => 'A world that grows with them',
					'text'   => 'Your child explores an immersive learning world that opens up as they progress.',
					'points' => array(
						'New areas unlock with mastery',
						'Learning quests and characters',
						'Safe, ad-free and child-friendly',
					),
				),
			),
			'placeholder' => 'Placeholder: app screenshot or short screen recording of this activity',
		),

		'ai' => array(
			'kicker' => 'The clever part',
			'title'  => 'It listens to every answer',
			'lede'   => 'Not just right or wrong. The Kids Gate reads speed, confidence and mistake patterns, then adjusts the very next activity. See for yourself:',
			'reassess' => 'And every two weeks, the whole path is re-checked in a full reassessment.',
			'nodes'  => array(
				array( 'title' => 'Your child answers', 'text' => 'Every tap, every answer, every hesitation is a signal.' ),
				array( 'title' => 'The AI interprets', 'text' => 'Was it mastery, a lucky guess, or a gap? The system works it out.' ),
				array( 'title' => 'The path adjusts', 'text' => 'The next activity gets easier, harder, or approaches the idea from a new angle.' ),
				array( 'title' => 'Progress updates', 'text' => 'Mastery scores update live, and everything is re-checked in a full reassessment every two weeks.' ),
			),
			'demo_label'   => 'Try it. Answer like your child might:',
			'demo_question' => 'What is 7 × 8?',
			'demo_correct_btn' => '56',
			'demo_wrong_btn'   => '54',
			'demo_correct' => 'Nice! The next question would step up to division with remainders, keeping the challenge just right.',
			'demo_wrong'   => 'No problem. The Kids Gate would slow down here: a visual times-table activity comes next, then we try again.',
		),

		'dashboard' => array(
			'kicker' => 'For you, the parent',
			'title'  => 'See everything. Miss nothing.',
			'lede'   => 'The parent dashboard shows what your child mastered, what they\'re finding difficult and what to celebrate at dinner.',
			'points' => array(
				array( 'title' => 'Progress at a glance', 'text' => 'Lessons completed, time spent and streaks for every child.' ),
				array( 'title' => 'Mastery tracking', 'text' => 'Topic-by-topic scores show exactly what has stuck.' ),
				array( 'title' => 'Difficult topics flagged', 'text' => 'Spot struggles early, before they become gaps.' ),
				array( 'title' => 'Smart recommendations', 'text' => 'Simple suggestions for how to help, when help is needed.' ),
			),
			'img_alt'   => 'The Kids Gate parent dashboard showing activity and progress charts',
			'cta_label' => 'Explore the parent dashboard',
		),

		'rewards' => array(
			'kicker' => 'Motivation, built in',
			'title'  => 'Tokens, trophies and a world to unlock',
			'lede'   => 'Rewards celebrate consistency, a little every day beats a lot once a week.',
			'cards'  => array(
				array( 'title' => 'The Kids Gate Store', 'text' => 'Spend hard-earned tokens on avatars, outfits and fun surprises.' ),
				array( 'title' => 'Global leaderboards', 'text' => 'Friendly country and grade rankings celebrate consistent learners.' ),
				array( 'title' => 'Monthly prize draws', 'text' => 'Active learners are entered into monthly draws with real prizes.' ),
			),
			'map_alt'     => 'The Kids Gate virtual world map with islands to explore',
			'map_caption' => 'The Kids Gate world. New areas unlock as your child progresses.',
			'cta_label'   => 'Peek at the leaderboard',
		),

		'testimonials' => array(
			'kicker' => 'From families like yours',
			'title'  => 'What parents are saying',
			'lede'   => '',
			'flag'   => 'Placeholder',
			'items'  => array(
				array(
					'quote' => 'Placeholder testimonial. Replace with a verified parent quote about their child\'s enthusiasm for daily lessons.',
					'name'  => 'Parent name',
					'meta'  => 'Parent of a Grade 2 learner',
				),
				array(
					'quote' => 'Placeholder testimonial. Replace with a verified quote about progress visible in the parent dashboard.',
					'name'  => 'Parent name',
					'meta'  => 'Parent of two The Kids Gate learners',
				),
				array(
					'quote' => 'Placeholder testimonial. Replace with a verified quote about the free trial converting a sceptical parent.',
					'name'  => 'Parent name',
					'meta'  => 'Parent of a Grade 5 learner',
				),
			),
		),

		'pricing' => array(
			'kicker'    => 'Simple pricing',
			'title'     => 'Less than a tutor\'s single hour, <span class="kg-hl-teal">every month</span>',
			'lede'      => 'One subscription per child, up to six children per family. Start with 30 days free.',
			'cta_label' => 'See full pricing',
		),

		'final' => array(
			'title' => 'Give your child a month of <span class="kg-squiggle--teal kg-squiggle">loving</span> learning. On us.',
			'lede'  => 'Set up takes five minutes. The first smile takes one lesson.',
		),
	),

	/* ------------------------------------------------------- How It Works */
	'hiw' => array(
		'hero' => array(
			'kicker' => 'How It Works',
			'title'  => 'From first quiz to first trophy',
			'lede'   => 'Here\'s exactly what happens after you press Start Free Trial, step by step, no jargon.',
		),
		'steps' => array(
			array(
				'title' => '1. A friendly assessment',
				'text'  => 'Your child plays through a short placement quiz for each subject. It feels like a game; behind the scenes it maps their true level across reading, vocabulary, numbers and logic.',
				'detail' => 'No scores are shown to the child, just encouragement. You see the results in your dashboard.',
			),
			array(
				'title' => '2. A path made for one child only',
				'text'  => 'The AI assembles a personalised sequence from 1,800+ lessons. Two children in the same grade can get completely different paths.',
				'detail' => 'The path targets the edge of your child\'s ability: never boring, never overwhelming.',
			),
			array(
				'title' => '3. The daily 20-minute session',
				'text'  => 'Each day, your child gets a fresh mix: a warm-up game, a new concept taught through a story or song, practice quizzes and a puzzle to finish.',
				'detail' => 'Watch the demo video below to see a real session in motion.',
			),
			array(
				'title' => '4. The AI responds to every answer',
				'text'  => 'A wrong answer isn\'t a fail. It\'s information. The system re-teaches the idea from a different angle before moving on.',
				'detail' => 'Speed, hesitation and mistake patterns all shape what comes next.',
			),
			array(
				'title' => '5. Reassessment every two weeks',
				'text'  => 'Every fortnight, The Kids Gate automatically re-checks your child\'s level so the path never drifts from reality.',
				'detail' => 'Levelled up? The path accelerates. Need consolidation? It circles back gently.',
			),
			array(
				'title' => '6. Tokens, rewards and the world',
				'text'  => 'Completed lessons earn tokens to spend in The Kids Gate Store, and consistent streaks unlock new areas of the virtual world.',
				'detail' => 'Rewards celebrate showing up. Consistency is what compounds.',
			),
			array(
				'title' => '7. You watch it all happen',
				'text'  => 'Your parent dashboard updates after every session: progress, mastery, tricky topics and recommendations.',
				'detail' => 'Multiple children? Each gets their own profile and their own path.',
			),
		),
		'video' => array(
			'kicker' => 'See it for yourself',
			'title'  => 'A real lesson, in two minutes',
			'lede'   => 'This is the actual in-app experience, no mock-ups.',
			'play'   => 'Play the demo',
		),
		'session' => array(
			'kicker' => 'A typical day',
			'title'  => 'Anatomy of a 20-minute session',
			'lede'   => '',
			'items'  => array(
				array( 'time' => 'Minutes 0–3', 'title' => 'Warm-up game', 'text' => 'A quick, confidence-building game eases your child in.' ),
				array( 'time' => 'Minutes 3–10', 'title' => 'New concept', 'text' => 'A story, song or visual explanation introduces today\'s idea.' ),
				array( 'time' => 'Minutes 10–17', 'title' => 'Adaptive practice', 'text' => 'Quizzes and activities that adjust difficulty in real time.' ),
				array( 'time' => 'Minutes 17–20', 'title' => 'Puzzle & rewards', 'text' => 'A closing puzzle, tokens earned, streak extended. Done for today!' ),
			),
		),
		'cta' => array(
			'title' => 'The best way to understand it? Watch your child try it.',
			'lede'  => '30 days free. No credit card. Cancel anytime.',
		),
	),

	/* ------------------------------------------------------------ Features */
	'features' => array(
		'hero' => array(
			'kicker' => 'Features',
			'title'  => 'Everything inside the Gate',
			'lede'   => 'Built for children who want to play, and parents who want results.',
		),
		'spotlight_ai' => array(
			'kicker' => 'Flagship feature',
			'title'  => 'Adaptive AI personalisation',
			'text'   => 'The heart of The Kids Gate. Every answer your child gives, right, wrong, fast or slow, feeds a learning model that adjusts the very next activity. A full reassessment every two weeks keeps the path honest.',
			'points' => array(
				'Adjusts after every single answer',
				'Re-teaches missed ideas from new angles',
				'Automatic reassessment every two weeks',
			),
			'aiviz'  => array(
				'label'  => 'Adaptive engine',
				'live'   => 'Live',
				'answer' => 'Answer in',
				'core'   => 'AI model',
				'action' => 'Reinforce',
				'map'    => 'Knowledge map',
				'missed' => 'missed',
				'got'    => 'correct',
				'skills' => array( 'Times tables', 'Fractions', 'Phonics', 'Vocabulary' ),
				'phases' => array(
					'New answer received',
					'Reading speed, confidence and mistakes',
					'Gap found',
					'Re-teaching from a new angle',
					'Mastery climbing',
				),
			),
		),
		'spotlight_dash' => array(
			'kicker' => 'For parents',
			'title'  => 'A dashboard that answers "how\'s it going?"',
			'text'   => 'Progress summaries, mastery scores, difficult-topic alerts and practical recommendations, for every child on your account.',
			'points' => array(
				'Per-child profiles and progress',
				'Mastery tracked topic by topic',
				'Struggles flagged early, with suggestions',
			),
			'img_alt' => 'Parent dashboard preview',
		),
		'grid' => array(
			'kicker' => 'And the rest of the toolbox',
			'title'  => 'Eight more reasons children press play',
			'lede'   => '',
			'items'  => array(
				array( 'title' => 'Cambridge English', 'text' => 'Phonics to grammar, aligned with Cambridge English stages for ages 5–12.' ),
				array( 'title' => 'International Maths', 'text' => 'A complete Grades 1–6 curriculum taught visually and practised through play.' ),
				array( 'title' => '1,800+ lessons', 'text' => 'Games, stories, quizzes, songs and puzzles, fresh content at every level.' ),
				array( 'title' => 'Immersive virtual world', 'text' => 'A learning world that expands as your child masters new topics.' ),
				array( 'title' => 'Tokens & the Store', 'text' => 'Real effort earns tokens to spend on avatars and surprises.' ),
				array( 'title' => 'Global leaderboard', 'text' => 'Safe, friendly rankings by country and grade, plus monthly prize draws.' ),
				array( 'title' => 'Teacher dashboards', 'text' => 'Optional classroom tools for schools: progress graphs and mastery maps.' ),
				array( 'title' => 'Safe & ad-free', 'text' => 'No advertising, no chat with strangers, no dark patterns. Just learning.' ),
			),
		),
		'cta' => array(
			'title' => 'See the features in action',
			'lede'  => 'The 30-day free trial includes everything on this page.',
		),
	),

	/* ------------------------------------------------------------- Parents */
	'parents' => array(
		'hero' => array(
			'kicker' => 'For Parents',
			'title'  => 'You stay in the loop, without hovering',
			'lede'   => 'The Kids Gate is built on a simple promise: your child gets the fun, you get the facts.',
		),
		'tour' => array(
			'kicker' => 'Dashboard walkthrough',
			'title'  => 'Your dashboard, annotated',
			'lede'   => 'Tap any area to see what it tells you, then switch between the Activity and Performance views.',
			'activity_alt'      => 'The Kids Gate parent dashboard, Activity view',
			'performance_alt'   => 'The Kids Gate parent dashboard, Performance view',
			'activity_label'    => 'Progress Dashboard: Activity',
			'performance_label' => 'Progress Dashboard: Performance',
			// Items 1–5 map to the same five regions on both screenshots
			// (the layouts are identical); _a = Activity view, _b = Performance view.
			'items' => array(
				array(
					'title_a' => 'Activity totals',
					'text_a'  => 'A quick snapshot: GATE tokens earned, activities completed and total visits.',
					'title_b' => 'Performance totals',
					'text_b'  => 'A quick snapshot: quizzes completed, your child\'s ranking and coins earned.',
				),
				array(
					'title_a' => 'Achievement chart',
					'text_a'  => 'How many GATE tokens your child has earned versus the average for other children in your country, state and city, including the top ten achievers.',
					'title_b' => 'Progress chart',
					'text_b'  => 'How many hard, medium and easy quizzes your child has completed, compared with other children in your country, state and city.',
				),
				array(
					'title_a' => 'Activities chart',
					'text_a'  => 'The number of course modules completed versus other children in your country, state and city, showing the top ten achievers.',
					'title_b' => 'Progress report',
					'text_b'  => 'A detailed, tabular view of each completed quiz, with more on every attempt your child has made.',
				),
				array(
					'title_a' => 'Visits chart',
					'text_a'  => 'The total number of visits compared with other children in your country, state and city.',
					'title_b' => 'Quiz performance report',
					'text_b'  => 'The percentage of hard, medium and easy quizzes your child has completed versus other children locally.',
				),
				array(
					'title_a' => 'Activity records report',
					'text_a'  => 'Detailed, tabular records of when each module or activity was completed and which subject it belongs to.',
					'title_b' => 'Leaders report & chart',
					'text_b'  => 'Quizzes completed at the harder difficulty, ranked against the top ten children in your country and city in the same grade.',
				),
			),
			// The sixth card flips the whole view (image + cards 1–5).
			'toggle' => array(
				'title_a' => 'See the Performance view',
				'text_a'  => 'You\'re viewing the Activity dashboard. Tap to switch to Performance: quizzes, ranking and progress.',
				'title_b' => 'Back to the Activity view',
				'text_b'  => 'You\'re viewing the Performance dashboard. Tap to switch back to Activity: tokens, activities and visits.',
			),
		),
		'profiles' => array(
			'kicker' => 'Built for families',
			'title'  => 'Up to six children, one account',
			'lede'   => 'Each child gets their own profile, their own path, their own rewards, and you see them all in one place.',
			'items'  => array(
				array( 'title' => 'Individual profiles', 'text' => 'Separate progress, levels and rewards for every child.' ),
				array( 'title' => 'Different subjects per child', 'text' => 'One child can study both subjects while another does just Maths.' ),
				array( 'title' => 'Fair family pricing', 'text' => 'Additional children get discounted rates, see the pricing page.' ),
			),
		),
		'perks' => array(
			'kicker' => 'More for parents',
			'title'  => 'The little things that make life easier',
			'lede'   => 'Thoughtful extras built around how real families actually use The Kids Gate.',
			'items'  => array(
				array( 'title' => 'Offline Mode', 'text' => 'Lessons are cached so your child can keep learning even without Wi-Fi, great for long trips.' ),
				array( 'title' => 'Cancel Any Time', 'text' => 'No lock-in contracts. Cancel your subscription at any time directly from your account settings.' ),
				array( 'title' => 'Weakness Alerts', 'text' => 'Flag topics where your child needs extra support so you can step in or discuss with their teacher.' ),
			),
		),
		'safety' => array(
			'kicker' => 'Safety first',
			'title'  => 'A walled garden, in the best way',
			'lede'   => '',
			'items'  => array(
				array( 'title' => 'Completely ad-free', 'text' => 'No advertising of any kind, anywhere in the app.' ),
				array( 'title' => 'No contact with strangers', 'text' => 'No open chat. Leaderboards show safe display names and avatars only.' ),
				array( 'title' => 'You hold the keys', 'text' => 'Subscription, profiles, daily screen-time limits and settings all live in the parent account.' ),
			),
		),
		'join' => array(
			'kicker' => 'Getting started',
			'title'  => 'Up and running in four easy steps',
			'lede'   => 'From sign-up to your first progress report, here\'s exactly how it works.',
			'items'  => array(
				array( 'title' => 'Create a family account', 'text' => 'Sign up and add your children. Each child gets their own profile and personalised learning path.' ),
				array( 'title' => 'Child takes the assessment', 'text' => 'A short diagnostic test maps your child\'s exact level across English and Maths.' ),
				array( 'title' => 'Learning begins', 'text' => 'Your child works through their personalised lesson plan. You can watch their progress in real time.' ),
				array( 'title' => 'You stay informed', 'text' => 'Weekly reports land in your inbox. Log in any time to see detailed progress, streaks and achievements.' ),
			),
		),
		'faq' => array(
			'kicker' => 'Parent questions',
			'title'  => 'Asked all the time',
			'lede'   => '',
			'items'  => array(
				array(
					'q' => 'How much screen time is this?',
					'a' => 'The Kids Gate is designed around one short session of about 20 minutes per day. The app encourages stopping at a natural finish point rather than endless scrolling.',
				),
				array(
					'q' => 'Will it actually match my child\'s school work?',
					'a' => 'The Kids Gate teaches Cambridge English and International Maths for Grades 1–6. The adaptive path means it complements school by filling gaps and stretching strengths rather than duplicating homework.',
				),
				array(
					'q' => 'What if my child finds it too easy or too hard?',
					'a' => 'That\'s exactly what the AI prevents. Difficulty adjusts after every answer, and a full reassessment every two weeks keeps the level honest.',
				),
				array(
					'q' => 'Can I see what my child did each day?',
					'a' => 'Yes. The parent dashboard shows lessons completed, time spent, mastery changes and anything worth celebrating or watching.',
				),
				array(
					'q' => 'Is the reward system healthy?',
					'a' => 'Rewards celebrate consistency, not speed or competition. Tokens come from completed learning, and the leaderboard is designed to encourage showing up, not to pressure children.',
				),
			),
		),
	),

	/* ------------------------------------------------------------- Pricing */
	'pricing' => array(
		'hero' => array(
			'kicker' => 'Pricing',
			'title'  => 'Honest pricing for real families',
			'lede'   => 'Every plan starts with a 30-day free trial. No credit card required. Cancel anytime.',
		),
		'toggle' => array(
			'monthly' => 'Monthly',
			'yearly'  => 'Annual',
			'save'    => 'Save 20%',
		),
		'save_label' => 'Save {n}%',
		'activation_faq_q' => 'What is the one-time activation fee?',
		'activation_help'  => 'About the one-time activation fee',
		'activation_info'  => 'A one-time fee per child — and per school student — charged once while continuously enrolled. It carries over when you switch from monthly to annual (no re-charge), and only applies again if an account lapses or cancels and then rejoins. It funds the token rewards and in-app learning extras that keep children coming back and making steady progress.',
		'plans' => array(
			'one' => array(
				'name'   => 'One subject',
				'desc'   => 'Cambridge English OR International Maths',
				'features' => array(
					'Full adaptive AI personalisation',
					'All lessons for your subject',
					'Tokens, rewards & the virtual world',
					'Parent dashboard included',
					'Reassessment every two weeks',
				),
			),
			'two' => array(
				'name'   => 'Two subjects',
				'desc'   => 'Cambridge English AND International Maths',
				'flag'   => 'Most popular',
					'vs_singles' => 'for two single subjects',
				'features' => array(
					'Everything in One subject',
					'Both full curriculums',
					'One combined daily session',
					'Cross-subject progress view',
					'Best value per subject',
				),
			),
			'per_month'     => '/month',
			'billed_yearly' => 'billed yearly',
			'addl_note'     => 'Additional children from {price}/month',
		),
		'builder' => array(
			'kicker' => 'Build your family plan',
			'title'  => 'Mix and match for up to six children',
			'lede'   => 'Choose each child\'s subjects. The child with the most subjects pays the standard rate; every other child gets the additional-child rate.',
			'add_child'    => 'Add another child',
			'remove_child' => 'Remove this child',
			'english'      => 'Cambridge English',
			'maths'        => 'International Maths',
			'summary_title' => 'Your family plan',
			'total_label'   => 'Total',
			'select_cta'    => 'Choose this plan',
			'select_note'   => 'You\'ll be taken to our support team to finish setting up, online checkout is coming soon.',
			'trial_note'    => 'Your 30-day free trial covers everything in this plan.',
				'trial_lead'    => 'The first 30 days are free, then:',
			'activation_label' => 'One-time activation',
			'first_payment'    => 'First payment today',
			'tax_note'         => 'Extra regional fees and taxes may apply at checkout.',
		),
		'calc' => array(
			'per_month'     => '/month',
			'billed_yearly' => '/month, billed yearly',
			'billed_total'  => '{price} billed today',
			'child'         => 'Child',
			'full_rate'     => 'standard rate',
			'addl_rate'     => 'additional-child rate',
			'one_subject'   => '1 subject',
			'two_subjects'  => '2 subjects',
			'activation_sub' => '{price} per child × {n}',
		),
		'disclaimer' => 'How family billing works: if your children study different numbers of subjects, the child with the most subjects is billed at the standard first-child rate. Every other child is billed at the additional-child rate that matches their own subject count. The same rule applies to monthly and annual billing.',
		'faq' => array(
			'kicker' => 'Pricing questions',
			'title'  => 'Before you ask',
			'lede'   => '',
			'items'  => array(
				array(
					'q' => 'What happens after the free trial?',
					'a' => 'After 30 days you choose a plan to continue. Nothing is charged automatically, there\'s no credit card on file unless you add one.',
				),
				array(
					'q' => 'Is a credit card required to start?',
					'a' => 'No. You can start and complete the entire 30-day trial without entering any payment details.',
				),
				array(
					'q' => 'Can we cancel at any time?',
					'a' => 'Yes. Cancel whenever you like, your plan simply runs to the end of the period you\'ve paid for.',
				),
				array(
					'q' => 'Can we switch plans?',
					'a' => 'Yes, you can move between one-subject and two-subject plans, or switch between monthly and annual billing, at any time.',
				),
				array(
					'q' => 'How do additional children work?',
					'a' => 'A family account holds up to six children. The child with the most subjects pays the standard rate; each other child pays the discounted additional-child rate for their own subjects.',
				),
				array(
					'q' => 'Can my children study different subjects?',
					'a' => 'Absolutely. One child can study both subjects while a sibling does just English or just Maths, the price builder above shows exactly what that costs.',
				),
				array(
					'q' => 'How does annual billing work?',
					'a' => 'Annual plans are billed once per year at a discounted monthly equivalent, you save around 20% versus paying monthly.',
				),
			),
		),
	),

	/* ------------------------------------------------------------- Schools */
	'schools' => array(
		'hero' => array(
			'kicker' => 'For Schools & Teachers',
			'title'  => 'Personalised learning for every seat in the class',
			'lede'   => 'The Kids Gate complements classroom teaching with adaptive practice, and gives teachers the visibility tutors charge for.',
		),
		'align' => array(
			'kicker' => 'Curriculum fit',
			'title'  => 'Aligned with what you already teach',
			'lede'   => '',
			'items'  => array(
				array( 'title' => 'Cambridge English', 'text' => 'Stage-aligned English content from phonics through to confident reading and writing.' ),
				array( 'title' => 'International Maths', 'text' => 'A complete Grades 1–6 progression that mirrors international frameworks.' ),
				array( 'title' => 'Grades 1–6', 'text' => 'One platform for the whole primary school, from first phonics to pre-secondary.' ),
			),
		),
		'value' => array(
			'kicker' => 'In practice',
			'title'  => 'Classroom and home, working together',
			'lede'   => 'However you teach, The Kids Gate slots in and adapts to every learner without adding to your workload.',
			'items'  => array(
				array( 'tag' => 'Live differentiation', 'title' => 'In class', 'text' => 'Use The Kids Gate for differentiated practice, every student works at their own level during the same session.' ),
				array( 'tag' => 'Homework, sorted', 'title' => 'At home', 'text' => 'Homework that marks itself, adapts itself and reports itself back to you.' ),
				array( 'tag' => 'Pinpoint help', 'title' => 'For interventions', 'text' => 'Difficult-topic flags show exactly which students need help, on exactly which concepts.' ),
			),
		),
		'viewtoggle' => array(
			'aria'      => 'Switch dashboard view',
			'teacher'   => 'Teacher',
			'principal' => 'Principal',
		),
		'dash' => array(
			'kicker' => 'Teacher dashboard',
			'title'  => 'Class-level insights at a glance',
			'text'   => 'A real-time view of your whole class with no marking, no spreadsheets and no guesswork.',
			'feats'  => array(
				array( 'title' => 'Assessment score chart', 'text' => 'Track comprehension scores across Quiz 1–6 for the whole class.' ),
				array( 'title' => 'Total session breakdown', 'text' => 'See each student\'s share of total sessions in a clear donut chart.' ),
				array( 'title' => 'Avg score per student', 'text' => 'Compare average quiz scores across every student in the class.' ),
				array( 'title' => 'Activity completion', 'text' => 'Monitor how many activities each student has completed this period.' ),
			),
			'img_alt' => 'The Kids Gate teacher dashboard showing class assessment scores, session breakdown and activity completion',
		),
		'principal' => array(
			'kicker' => 'Principal view',
			'title'  => 'School-wide insight at a glance',
			'text'   => 'A bird\'s-eye view across every class, with the data that matters most for leadership decisions.',
			'feats'  => array(
				array( 'title' => 'Cross-class comparison', 'text' => 'Compare assessment scores between Class A and Class B on the same chart.' ),
				array( 'title' => 'Grade & subject filters', 'text' => 'Filter by Grade 1–6 and switch between Maths and English instantly.' ),
				array( 'title' => 'Avg earned tokens', 'text' => 'See which classes are most engaged via token earnings per class.' ),
				array( 'title' => 'Avg completed assessments', 'text' => 'Track how many assessments and videos each class completes on average.' ),
			),
			'img_alt' => 'The Kids Gate principal dashboard comparing classes by assessment scores, tokens and completion',
		),
		'testimonial' => array(
			'flag'  => 'Placeholder',
			'quote' => 'Placeholder: verified teacher or principal quote to be added here once available.',
			'name'  => 'Teacher name',
			'meta'  => 'School name, country',
		),
		'curriculum_cta' => 'Download Curriculum Overview',
		'curriculum_note' => 'Placeholder: the downloadable curriculum PDF is being finalised.',
		'form' => array(
			'kicker' => 'Get started',
			'title'  => 'Request school information',
			'lede'   => 'Tell us about your school and we\'ll come back with pricing, onboarding details and a pilot proposal.',
				'pricing_note' => 'Schools save <span class="kg-hl-teal">up to 10%</span> per student versus standard rates, billed annually.',
			'name'        => 'Your name',
			'email'       => 'Work email',
			'school'      => 'School name',
			'role'        => 'Your role',
			'role_opts'   => array( 'Teacher', 'Head of Department', 'Principal / Head', 'School Administrator', 'Other' ),
			'students'    => 'Approximate number of students',
			'message'     => 'Anything else we should know?',
			'submit'      => 'Request School Information',
			'success_title' => 'Thank you, request received',
			'success_text'  => 'Our schools team will reply within two working days. (Note: the enquiry backend is not yet connected, this submission is recorded for analytics only. See the support email in the footer for urgent enquiries.)',
		),
	),

	/* --------------------------------------------------------- Leaderboard */
	'leaderboard' => array(
		'hero' => array(
			'kicker' => 'Leaderboard',
			'title'  => 'A friendly race around the world',
			'lede'   => 'Learners everywhere earn tokens for consistency. The leaderboard celebrates showing up, every lesson counts.',
		),
		'filters' => array(
			'scope'   => array( 'Global', 'My country' ),
			'grades'  => array( 'All grades', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6' ),
			'subjects' => array( 'All subjects', 'English', 'Maths' ),
			'periods'  => array( 'This week', 'This month', 'All time' ),
		),
		'rows' => array(
			array( 'name' => 'Star_Naya', 'country' => 'Indonesia', 'code' => 'ID', 'grade' => 'Grade 3', 'grade_n' => 3, 'subject' => 'maths', 'tokens' => '2,840', 'move' => 'up' ),
			array( 'name' => 'MathTiger', 'country' => 'Thailand', 'code' => 'TH', 'grade' => 'Grade 5', 'grade_n' => 5, 'subject' => 'maths', 'tokens' => '2,715', 'move' => 'up' ),
			array( 'name' => 'BookwormBen', 'country' => 'Australia', 'code' => 'AU', 'grade' => 'Grade 4', 'grade_n' => 4, 'subject' => 'english', 'tokens' => '2,650', 'move' => 'down' ),
			array( 'name' => 'PuzzlePim', 'country' => 'Thailand', 'code' => 'TH', 'grade' => 'Grade 2', 'grade_n' => 2, 'subject' => 'both', 'tokens' => '2,590', 'move' => 'up' ),
			array( 'name' => 'RocketRani', 'country' => 'Indonesia', 'code' => 'ID', 'grade' => 'Grade 6', 'grade_n' => 6, 'subject' => 'maths', 'tokens' => '2,470', 'move' => 'same' ),
			array( 'name' => 'CleverCleo', 'country' => 'Singapore', 'code' => 'SG', 'grade' => 'Grade 1', 'grade_n' => 1, 'subject' => 'english', 'tokens' => '2,395', 'move' => 'up' ),
			array( 'name' => 'GateGazer', 'country' => 'Malaysia', 'code' => 'MY', 'grade' => 'Grade 4', 'grade_n' => 4, 'subject' => 'both', 'tokens' => '2,310', 'move' => 'down' ),
			array( 'name' => 'SunnySom',    'country' => 'Thailand',        'code' => 'TH', 'grade' => 'Grade 3', 'grade_n' => 3, 'subject' => 'english', 'tokens' => '2,255', 'move' => 'up' ),
			array( 'name' => 'MindMapMia',  'country' => 'Philippines',     'code' => 'PH', 'grade' => 'Grade 1', 'grade_n' => 1, 'subject' => 'english', 'tokens' => '2,190', 'move' => 'up' ),
			array( 'name' => 'QuizKingKit', 'country' => 'India',           'code' => 'IN', 'grade' => 'Grade 6', 'grade_n' => 6, 'subject' => 'maths',   'tokens' => '2,120', 'move' => 'down' ),
			array( 'name' => 'WordWizWin',  'country' => 'United Kingdom',  'code' => 'GB', 'grade' => 'Grade 3', 'grade_n' => 3, 'subject' => 'english', 'tokens' => '2,055', 'move' => 'up' ),
			array( 'name' => 'NumNinja',    'country' => 'South Korea',     'code' => 'KR', 'grade' => 'Grade 5', 'grade_n' => 5, 'subject' => 'maths',   'tokens' => '1,990', 'move' => 'same' ),
			array( 'name' => 'SparkySam',   'country' => 'Australia',       'code' => 'AU', 'grade' => 'Grade 2', 'grade_n' => 2, 'subject' => 'both',    'tokens' => '1,935', 'move' => 'up' ),
			array( 'name' => 'GlowGabi',    'country' => 'Vietnam',         'code' => 'VN', 'grade' => 'Grade 4', 'grade_n' => 4, 'subject' => 'english', 'tokens' => '1,880', 'move' => 'down' ),
			array( 'name' => 'BrainBolt',   'country' => 'United States',   'code' => 'US', 'grade' => 'Grade 6', 'grade_n' => 6, 'subject' => 'both',    'tokens' => '1,820', 'move' => 'up' ),
			array( 'name' => 'TurboTam',    'country' => 'Thailand',        'code' => 'TH', 'grade' => 'Grade 1', 'grade_n' => 1, 'subject' => 'maths',   'tokens' => '1,760', 'move' => 'down' ),
			array( 'name' => 'EagleEli',    'country' => 'UAE',             'code' => 'AE', 'grade' => 'Grade 5', 'grade_n' => 5, 'subject' => 'english', 'tokens' => '1,700', 'move' => 'up' ),
			array( 'name' => 'RainbowRia',  'country' => 'Indonesia',       'code' => 'ID', 'grade' => 'Grade 2', 'grade_n' => 2, 'subject' => 'english', 'tokens' => '1,640', 'move' => 'same' ),
			array( 'name' => 'JetJoy',      'country' => 'Japan',           'code' => 'JP', 'grade' => 'Grade 3', 'grade_n' => 3, 'subject' => 'maths',   'tokens' => '1,575', 'move' => 'up' ),
			array( 'name' => 'ZenZara',     'country' => 'Malaysia',        'code' => 'MY', 'grade' => 'Grade 6', 'grade_n' => 6, 'subject' => 'english', 'tokens' => '1,510', 'move' => 'down' ),
			array( 'name' => 'FlashFinn',   'country' => 'Canada',          'code' => 'CA', 'grade' => 'Grade 4', 'grade_n' => 4, 'subject' => 'maths',   'tokens' => '1,455', 'move' => 'up' ),
			array( 'name' => 'LucyLumos',   'country' => 'New Zealand',     'code' => 'NZ', 'grade' => 'Grade 1', 'grade_n' => 1, 'subject' => 'both',    'tokens' => '1,390', 'move' => 'up' ),
			array( 'name' => 'ChampChris',  'country' => 'Singapore',       'code' => 'SG', 'grade' => 'Grade 5', 'grade_n' => 5, 'subject' => 'both',    'tokens' => '1,330', 'move' => 'same' ),
			array( 'name' => 'PixelPat',    'country' => 'Hong Kong',       'code' => 'HK', 'grade' => 'Grade 2', 'grade_n' => 2, 'subject' => 'maths',   'tokens' => '1,275', 'move' => 'down' ),
			array( 'name' => 'BlazeBlue',   'country' => 'Sri Lanka',       'code' => 'LK', 'grade' => 'Grade 3', 'grade_n' => 3, 'subject' => 'both',    'tokens' => '1,215', 'move' => 'up' ),
			array( 'name' => 'CosmoKai',    'country' => 'United Kingdom',  'code' => 'GB', 'grade' => 'Grade 4', 'grade_n' => 4, 'subject' => 'both',    'tokens' => '1,155', 'move' => 'down' ),
			array( 'name' => 'StellaStars', 'country' => 'South Africa',    'code' => 'ZA', 'grade' => 'Grade 1', 'grade_n' => 1, 'subject' => 'english', 'tokens' => '1,095', 'move' => 'up' ),
			array( 'name' => 'BoldBira',    'country' => 'Brazil',          'code' => 'BR', 'grade' => 'Grade 6', 'grade_n' => 6, 'subject' => 'maths',   'tokens' => '1,035', 'move' => 'same' ),
			array( 'name' => 'WaveWin',     'country' => 'Philippines',     'code' => 'PH', 'grade' => 'Grade 5', 'grade_n' => 5, 'subject' => 'english', 'tokens' => '975',   'move' => 'up' ),
			array( 'name' => 'ZestZoe',     'country' => 'India',           'code' => 'IN', 'grade' => 'Grade 2', 'grade_n' => 2, 'subject' => 'both',    'tokens' => '920',   'move' => 'down' ),
		),
		'demo_note' => 'Sample data shown. Live rankings appear in the app.',
		'count'      => '{n} learners',
		'count_one'  => '1 learner',
		'empty'      => 'No learners match these filters yet. Try widening your search.',
		'reset'      => 'Clear filters',
		'safety' => array(
			'kicker' => 'Safe by design',
			'title'  => 'Public, but private',
			'lede'   => '',
			'items'  => array(
				array( 'title' => 'Safe display names', 'text' => 'Children appear as chosen nicknames and avatars, never real names or photos.' ),
				array( 'title' => 'No location details', 'text' => 'Only country-level information is ever shown.' ),
				array( 'title' => 'Effort over speed', 'text' => 'Rankings reward consistent learning, not racing through content.' ),
			),
		),
		'prizes' => array(
			'kicker' => 'Monthly prize draws',
			'title'  => 'Every active learner gets a ticket',
			'text'   => 'Each month, learners who keep their streaks alive are entered into a prize draw. It\'s not about being #1. Showing up is what earns entries.',
		),
		'cta_label' => 'Join the Leaderboard',
	),

	/* --------------------------------------------------------------- About */
	'about' => array(
		'hero' => array(
			'kicker' => 'About Us',
			'title'  => 'Why we built the Gate',
			'lede'   => '',
		),
		'mission' => 'We believe every child deserves learning that meets them exactly where they are, and feels like <span class="kg-hl-amber">play</span>, not pressure.',
		'story' => array(
			'kicker' => 'Our story',
			'title'  => 'From watching children switch off, to watching them light up',
			'paras'  => array(
				'The Kids Gate is built by GATE Edutech Solutions Pty Ltd, a team of educators, engineers and parents across Australia and Southeast Asia.',
				'We watched bright children lose confidence in classrooms moving at someone else\'s pace, and we watched tutoring put real personalisation out of reach for most families.',
				'So we built the gate between where a child is and where they could be: an AI tutor with endless patience, a perfect memory, and a price normal families can afford.',
			),
		),
		'values' => array(
			'kicker' => 'What we stand for',
			'title'  => 'Three promises',
			'lede'   => '',
			'items'  => array(
				array( 'title' => 'Education first', 'text' => 'Real curriculums, Cambridge English and International Maths, not edutainment dressed up as learning.' ),
				array( 'title' => 'Personal, always', 'text' => 'AI exists here for one purpose: to give every child the path a private tutor would build.' ),
				array( 'title' => 'Safe and honest', 'text' => 'Ad-free, stranger-free, dark-pattern-free. Rewards encourage habits, not addiction.' ),
			),
		),
		'global' => array(
			'kicker' => 'Global from day one',
			'title'  => 'One platform, many languages',
			'text'   => 'The Kids Gate serves families in English, Bahasa Indonesia, Thai and Chinese, with local pricing and culturally adapted content, not just translations.',
		),
		'contact' => array(
			'kicker' => 'Company details',
			'title'  => 'Get in touch',
			'company' => 'GATE Edutech Solutions Pty Ltd',
			'note'    => 'For support questions, the fastest route is our Support page.',
			'cta'     => 'Contact Us',
		),
	),

	/* ------------------------------------------------------------ Sponsors */
	'sponsors' => array(
		'hero' => array(
			'kicker'        => 'Sponsors & Partners',
			'title'         => 'Help more children fall in love with learning',
			'lede'          => 'Partner with The Kids Gate to power rewards, fund learning access and put your brand in front of families worldwide, inside a safe, ad-free world built for ages 5–12.',
			'cta'           => 'Become a Sponsor',
			'cta_secondary' => 'See partnership tiers',
			'chips'         => array( 'Global reach', 'Ages 5–12', 'Safe & ad-free' ),
		),
		'impact' => array(
			'kicker' => 'Why sponsor The Kids Gate',
			'title'  => 'A growing world of young learners',
			'lede'   => 'Your support reaches engaged families every day across three languages and two core subjects.',
			'items'  => array(
				array( 'num' => '1800', 'suffix' => '+',   'label' => 'Lessons your support reaches' ),
				array( 'num' => '6',    'suffix' => '',    'label' => 'Grade levels, ages 5–12' ),
				array( 'num' => '4',    'suffix' => '',    'label' => 'Languages: English · Bahasa · Thai · Chinese' ),
				array( 'num' => '20',   'suffix' => 'min', 'label' => 'Of daily learning per child' ),
			),
			'note'   => 'Verified audience and engagement figures are available on request.',
		),
		'ways' => array(
			'kicker' => 'Ways to partner',
			'title'  => 'Choose the impact that fits your brand',
			'lede'   => 'Every partnership is shaped around what matters most to you.',
			'items'  => array(
				array( 'title' => 'Power the prize draws', 'text' => 'Sponsor the monthly prize draws that keep children motivated and coming back to learn.' ),
				array( 'title' => 'Fuel The Kids Gate Store', 'text' => 'Fund the token rewards and avatar items children unlock as they progress.' ),
				array( 'title' => 'Sponsor learning access', 'text' => 'Give children who could not otherwise afford it a full place on The Kids Gate to learn and play.' ),
			),
		),
		'tiers' => array(
			'kicker'  => 'Partnership tiers',
			'title'   => 'Find your level',
			'lede'    => 'Three starting points, and we happily tailor any of them.',
			'popular' => 'Most popular',
			'cta'     => 'Choose this tier',
			'note'    => 'All tiers are flexible. Tell us your goals and budget and we will build a partnership that works.',
			'items'   => array(
				array(
					'name'   => 'Community Sponsor',
					'price'  => 'Let\'s talk',
					'blurb'  => 'A warm welcome to The Kids Gate family.',
					'points' => array( 'Your logo on the Sponsors page', 'A social thank-you across our channels', 'Monthly impact summary' ),
				),
				array(
					'name'     => 'Learning Partner',
					'price'    => 'Let\'s talk',
					'blurb'    => 'Make a visible difference to learners.',
					'featured' => true,
					'points'   => array( 'Everything in Community Sponsor', 'Sponsor a monthly prize draw', 'Fund a set of learning places', 'A co-branded reward in the store', 'Quarterly partnership report' ),
				),
				array(
					'name'   => 'Founding Champion',
					'price'  => 'Let\'s talk',
					'blurb'  => 'Shape the future of the platform with us.',
					'points' => array( 'Everything in Learning Partner', 'A named prize draw', 'A dedicated The Kids Gate Store campaign', 'Featured placement on the homepage and app', 'A strategic, ongoing partnership' ),
				),
			),
		),
		'partners' => array(
			'kicker'      => 'Our partners',
			'title'       => 'Be one of our founding sponsors',
			'lede'        => 'This is where our partner logos will live. There is room for yours.',
			'placeholder' => 'Your logo here',
		),
		'steps' => array(
			'kicker' => 'How it works',
			'title'  => 'Partnering takes three simple steps',
			'lede'   => '',
			'items'  => array(
				array( 'title' => 'Get in touch', 'text' => 'Tell us about your brand and what you would like to achieve.' ),
				array( 'title' => 'Shape your impact', 'text' => 'We design a partnership around your goals, audience and budget.' ),
				array( 'title' => 'See the difference', 'text' => 'Receive regular reports on the children and learning you have supported.' ),
			),
		),
		'testimonial' => array(
			'flag'  => 'Partner testimonial placeholder',
			'quote' => 'A short quote from a future sponsor about the impact of partnering with The Kids Gate will appear here.',
			'name'  => 'Partner name',
			'meta'  => 'Organisation, country',
		),
		'form' => array(
			'kicker'        => 'Start the conversation',
			'title'         => 'Become a sponsor',
			'lede'          => 'Tell us a little about your organisation and a real person will be in touch.',
			'name'          => 'Your name',
			'email'         => 'Email address',
			'org'           => 'Organisation',
			'interest'      => 'What are you interested in?',
			'interest_opts' => array( 'Sponsoring prize draws', 'Funding The Kids Gate Store', 'Sponsoring learning access', 'Brand partnership', 'Something else' ),
			'budget'        => 'Indicative budget (optional)',
			'message'       => 'Tell us more (optional)',
			'submit'        => 'Send Sponsorship Enquiry',
			'success_title' => 'Enquiry received',
			'success_text'  => 'Thanks for your interest in partnering with The Kids Gate! Note for the site owners: the email backend is not yet connected, so this submission was recorded for analytics only. Until it is wired up, please also email the address below for a guaranteed response.',
		),
		'cta' => array(
			'title' => 'Let\'s build something brilliant for children',
			'lede'  => 'Whatever the size of your support, it helps a child somewhere enjoy learning a little more.',
		),
	),

	/* ------------------------------------------------------------- Support */
	'support' => array(
		'hero' => array(
			'kicker' => 'Support',
			'title'  => 'How can we help?',
			'lede'   => 'Search the answers below, browse by topic, or talk to a human. We\'re happy to help either way.',
		),
		'search_placeholder' => 'Search for answers, try "trial" or "subjects"',
		'no_results' => 'No answers match your search. Try different words, or send us a message below.',
		'cats' => array(
			'plans'   => 'Plans & pricing',
			'product' => 'Using The Kids Gate',
			'account' => 'Account & app',
		),
		'faq_items' => array(
			array(
				'cat' => 'product',
				'q'   => 'What is The Kids Gate?',
				'a'   => 'The Kids Gate is an AI-powered learning platform for children aged 5–12. It offers Cambridge English and International Maths lessons for Grades 1–6 through games, stories, quizzes, songs, puzzles and other interactive activities.',
			),
			array(
				'cat' => 'plans',
				'q'   => 'How does the 30-day free trial work?',
				'a'   => 'Families can try The Kids Gate free for 30 days. No credit card is required to start the trial, and you can cancel at any time.',
			),
			array(
				'cat' => 'product',
				'q'   => 'How does AI personalisation work?',
				'a'   => 'The Kids Gate adapts the learning experience based on each child\'s answers, mistakes, speed, confidence and progress. The learning level is also reassessed automatically every two weeks.',
			),
			array(
				'cat' => 'product',
				'q'   => 'How long should my child use The Kids Gate each day?',
				'a'   => 'The Kids Gate is designed around short daily learning sessions of approximately 20 minutes.',
			),
			array(
				'cat' => 'product',
				'q'   => 'Which subjects are available?',
				'a'   => 'Children can study Cambridge English, International Maths, or both subjects.',
			),
			array(
				'cat' => 'account',
				'q'   => 'Can I add more than one child to my account?',
				'a'   => 'Yes. A family account can include up to six children. Each child receives their own profile, progress tracking, rewards and learning path.',
			),
			array(
				'cat' => 'plans',
				'q'   => 'Can my children study different subjects?',
				'a'   => 'Yes. Children in the same family account can use different subject combinations. The child completing the most subjects is charged at the standard first-child rate; other children are charged at the relevant additional-child rate based on the number of subjects they study.',
			),
			array(
				'cat' => 'product',
				'q'   => 'How can parents monitor progress?',
				'a'   => 'The parent dashboard shows progress, mastery scores, difficult topics, recommendations and other useful learning information for every child on the account.',
			),
			array(
				'cat' => 'product',
				'q'   => 'Does The Kids Gate include rewards?',
				'a'   => 'Yes. Children earn tokens through learning activities and spend them in The Kids Gate Store. The Kids Gate also includes global leaderboards and monthly prize draws.',
			),
			array(
				'cat' => 'product',
				'q'   => 'Is The Kids Gate safe for children?',
				'a'   => 'The Kids Gate is designed as a safe, ad-free learning experience. Leaderboards use safe display names and avatars, and there is no open chat.',
			),
			array(
				'cat' => 'account',
				'q'   => 'Where can I download the app?',
				'a'   => 'Use the App Store and Google Play buttons in the footer of any page to download The Kids Gate. (Store links are placeholders until the confirmed listings are live.)',
			),
			array(
				'cat' => 'account',
				'q'   => 'How do I change or cancel my plan?',
				'a'   => 'Account-management tools are coming to the app. Until then, send us a message with the support form below, or email us, and we\'ll sort it out quickly.',
			),
			array(
				'cat' => 'plans',
				'q'   => 'Can schools and teachers use The Kids Gate?',
				'a'   => 'Yes. The Kids Gate includes optional teacher and school dashboards. Visit the <a href="{schools_url}">For Schools & Teachers</a> page and use the enquiry form there.',
			),
			array(
				'cat' => 'account',
				'q'   => 'I still need help. How can I contact the support team?',
				'a'   => 'Use the support form on this page, or email us directly at {support_email}. We aim to reply within two working days.',
			),
		),
		'form' => array(
			'kicker' => 'Still stuck?',
			'title'  => 'Send us a message',
			'lede'   => 'A real person reads every request.',
			'name'    => 'Your name',
			'email'   => 'Email address',
			'topic'   => 'Topic',
			'topics'  => array( 'Pricing & family plans', 'Free trial', 'Parent dashboard', 'Technical help', 'Schools & teachers', 'Something else' ),
			'account' => 'Account email or child profile name (optional)',
			'message' => 'How can we help?',
			'submit'  => 'Send Support Request',
			'success_title' => 'Request received',
			'success_text'  => 'Thanks for reaching out! Note for the site owners: the email backend is not yet connected, so this submission was recorded for analytics only. Until it\'s wired up, please also email the support address below for a guaranteed response.',
			'email_label' => 'Prefer email?',
		),
		'links' => array(
			'kicker' => 'Quick links',
			'title'  => 'Popular destinations',
			'items'  => array(
				array( 'label' => 'Pricing & family plans', 'slug' => 'pricing' ),
				array( 'label' => 'For Parents', 'slug' => 'parents' ),
				array( 'label' => 'How It Works', 'slug' => 'how-it-works' ),
				array( 'label' => 'Sponsors', 'slug' => 'sponsors' ),
			),
		),
		'helper' => array(
			'fab_label'   => 'Quick help',
			'title'       => 'The Kids Gate Help',
			'greeting'    => 'Hi! How can we help today? Pick a topic below 👇',
			'restart'     => 'Glad that helped! What else can we look into?',
			'helpful_q'   => 'Was this helpful?',
			'helpful_yes' => 'Yes, thanks!',
			'helpful_no'  => 'Not really',
			'back'        => '← Back',
			'no_help'     => 'No problem. Our support team can help you directly.',
			'no_help_cta' => 'Go to Support',
			'form_cta'    => 'Go to the support form',
			'nodes'       => array(
				array(
					'id'       => 'pricing',
					'label'    => 'Pricing & family plans',
					'children' => array(
						array(
							'id'     => 'pricing-cost',
							'label'  => 'How much does it cost?',
							'answer' => 'Plans start with one subject for the first child, with a lower rate for a second subject and for additional children. The <a href="{pricing_url}">pricing page</a> has an interactive builder that shows your exact monthly or yearly total.',
						),
						array(
							'id'       => 'pricing-add',
							'label'    => 'Adding more children',
							'children' => array(
								array(
									'id'     => 'pricing-add-count',
									'label'  => 'How many children can I add?',
									'answer' => 'A family account covers up to six children, each with their own profile, progress and rewards.',
								),
								array(
									'id'     => 'pricing-add-cost',
									'label'  => 'Do extra children pay full price?',
									'answer' => 'No. You pay full price only for the child doing the most subjects. The others are charged the lower additional-child rate based on what they study.',
								),
							),
						),
						array(
							'id'     => 'pricing-subjects',
							'label'  => 'Different subjects per child',
							'answer' => 'Yes. Children on the same account can study different subjects. You\'re only charged the extra cost for the additional subjects each child takes.',
						),
						array(
							'id'     => 'pricing-billing',
							'label'  => 'Monthly vs yearly',
							'answer' => 'You can pay monthly or yearly, and yearly billing works out cheaper over the year. Toggle between them on the <a href="{pricing_url}">pricing page</a>.',
						),
					),
				),
				array(
					'id'       => 'trial',
					'label'    => 'Starting the free trial',
					'children' => array(
						array(
							'id'     => 'trial-how',
							'label'  => 'How does the 30-day trial work?',
							'answer' => 'Every plan begins with 30 days free so your child can explore the full experience. Just create a family account in the app to start.',
						),
						array(
							'id'     => 'trial-card',
							'label'  => 'Do I need a credit card?',
							'answer' => 'No credit card is needed to start the free trial.',
						),
						array(
							'id'     => 'trial-cancel',
							'label'  => 'Cancelling',
							'answer' => 'You can cancel at any time. Until self-service cancellation is connected, our <a href="{support_url}">support team</a> will sort it for you.',
						),
					),
				),
				array(
					'id'       => 'dashboard',
					'label'    => 'Parent dashboard',
					'children' => array(
						array(
							'id'     => 'dash-what',
							'label'  => 'What can I see?',
							'answer' => 'Progress, time spent, mastery scores, difficult topics and simple recommendations for each child. The <a href="{parents_url}">For Parents page</a> has a full walkthrough.',
						),
						array(
							'id'     => 'dash-multi',
							'label'  => 'Tracking several children',
							'answer' => 'Each child has their own profile, so you can switch between them and see individual progress, streaks and rewards.',
						),
						array(
							'id'     => 'dash-help',
							'label'  => 'Spotting struggles',
							'answer' => 'The dashboard flags difficult topics early and suggests how to help, so small gaps don\'t become big ones.',
						),
					),
				),
				array(
					'id'       => 'using',
					'label'    => 'Using the app',
					'children' => array(
						array(
							'id'     => 'using-time',
							'label'  => 'How long each day?',
							'answer' => 'The Kids Gate is built around short daily sessions of about 20 minutes, enough to make progress without losing the fun.',
						),
						array(
							'id'     => 'using-subjects',
							'label'  => 'Subjects & grades',
							'answer' => 'Children can study Cambridge English, International Maths, or both, across Grades 1–6 (ages 5–12).',
						),
						array(
							'id'     => 'using-safe',
							'label'  => 'Is it safe and ad-free?',
							'answer' => 'Yes. The Kids Gate is designed as a safe, ad-free space for children to learn and play.',
						),
						array(
							'id'      => 'using-tech',
							'label'   => 'Something isn\'t working',
							'answer'  => 'Sorry about that! Updating to the latest app version fixes most issues. If it persists, use the support form with the topic "Technical help" and we\'ll dig in.',
							'escalate' => true,
						),
					),
				),
				array(
					'id'       => 'schools',
					'label'    => 'Schools & teachers',
					'children' => array(
						array(
							'id'     => 'schools-dash',
							'label'  => 'Teacher & principal dashboards',
							'answer' => 'Teachers get class-level progress and mastery views; principals get school-wide insight across classes. See the <a href="{schools_url}">For Schools & Teachers page</a>.',
						),
						array(
							'id'     => 'schools-pricing',
							'label'  => 'School or bulk pricing',
							'answer' => 'For classroom or whole-school plans, send us the details through the enquiry form on the <a href="{schools_url}">For Schools & Teachers page</a> and our schools team will be in touch.',
						),
						array(
							'id'     => 'schools-enquiry',
							'label'  => 'Make an enquiry',
							'answer' => 'The enquiry form on the <a href="{schools_url}">For Schools & Teachers page</a> goes straight to our schools team.',
						),
					),
				),
				array(
					'id'       => 'contact',
					'label'    => 'Contact the support team',
					'answer'   => 'You can reach a human via the support form, or email us at <a href="mailto:{support_email}">{support_email}</a>. We aim to reply within two working days.',
					'escalate' => true,
				),
			),
		),
	),

	/* ----------------------------------------------------------------- 404 */
	'e404' => array(
		'title'      => 'This gate leads nowhere!',
		'text'       => 'The page you\'re looking for has wandered off. Maybe it\'s exploring the virtual world. Let\'s get you back on the path.',
		'home_cta'   => 'Return Home',
		'support_cta' => 'Visit Support',
		'links_label' => 'Or jump straight to:',
	),

	'payment' => array(
		'kicker'      => 'Almost there',
		'title'       => 'Online payment isn\'t set up just yet',
		'text'        => 'Thanks for choosing your plan! We\'re still putting the finishing touches on secure checkout, so payments aren\'t available online right now. Our support team can help you get started or answer any questions in the meantime.',
		'support_cta' => 'Go to Support',
		'home_cta'    => 'Back to Home',
		'email_label' => 'Prefer email? Reach us at',
	),
);
