import { store, withSyncEvent } from '@wordpress/interactivity';

store('runpartner/event-content', {
	actions: {
		navigate: withSyncEvent(function* (e) {
			e.preventDefault();
			const href = e.currentTarget.href;
			if (!href) return;
			const { actions } = yield import('@wordpress/interactivity-router');
			yield actions.navigate(href);
		}),
	},
});
