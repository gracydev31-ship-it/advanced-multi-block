import { store, getContext } from '@wordpress/interactivity';

store('runpartner', {
	actions: {
		toggleCoachSection() {
			const context = getContext();
			context.isOpen = !context.isOpen;
		},
	},
});
