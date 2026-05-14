import { store, withSyncEvent, getElement } from '@wordpress/interactivity';

const autoplayIntervals = new Map();

function getCarouselContainer(el) {
	return el
		.closest('.event-archive-section')
		?.querySelector('.event-archive-carousel');
}

function getCardStep(container) {
	const card = container?.querySelector('.event-archive-card');
	return card ? card.offsetWidth + 16 : container?.offsetWidth ?? 0;
}

store('runpartner/events-archive', {
	actions: {
		navigate: withSyncEvent(function* (e) {
			e.preventDefault();
			const href = e.currentTarget.href;
			if (!href) return;

			const section = e.currentTarget.closest('.event-archive-section');
			const scrollTarget = section?.querySelector('.event-archive-section-title')?.id;

			const { actions } = yield import('@wordpress/interactivity-router');
			yield actions.navigate(href);

			if (scrollTarget) {
				const el = document.getElementById(scrollTarget);
				el?.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		}),

		carouselNext() {
			const container = getCarouselContainer(getElement().ref);
			if (!container) return;
			const step = getCardStep(container);
			const maxScroll = container.scrollWidth - container.clientWidth;
			if (container.scrollLeft + step >= maxScroll) {
				container.scrollTo({ left: 0, behavior: 'smooth' });
			} else {
				container.scrollBy({ left: step, behavior: 'smooth' });
			}
		},

		carouselPrev() {
			const container = getCarouselContainer(getElement().ref);
			if (!container) return;
			const step = getCardStep(container);
			if (container.scrollLeft - step <= 0) {
				container.scrollTo({
					left: container.scrollWidth,
					behavior: 'smooth',
				});
			} else {
				container.scrollBy({ left: -step, behavior: 'smooth' });
			}
		},

		pauseCarousel() {
			const el = getElement().ref;
			const id = el.dataset.carouselId;
			if (id && autoplayIntervals.has(id)) {
				clearInterval(autoplayIntervals.get(id));
				autoplayIntervals.delete(id);
			}
		},

		resumeCarousel() {
			const el = getElement().ref;
			const id = el.dataset.carouselId;
			if (!id) return;
			if (autoplayIntervals.has(id)) {
				clearInterval(autoplayIntervals.get(id));
			}
			autoplayIntervals.set(
				id,
				setInterval(() => {
					const wrapper = document.querySelector(
						`[data-carousel-id="${id}"]`
					);
					if (!wrapper) return;
					const container = wrapper.querySelector(
						'.event-archive-carousel'
					);
					if (!container) return;
					const step = getCardStep(container);
					const maxScroll = container.scrollWidth - container.clientWidth;
					if (container.scrollLeft + step >= maxScroll) {
						container.scrollTo({ left: 0, behavior: 'smooth' });
					} else {
						container.scrollBy({ left: step, behavior: 'smooth' });
					}
				}, 5000)
			);
		},
	},

	callbacks: {
		initCarousel() {
			document.querySelectorAll('[data-carousel-id]').forEach((wrapper) => {
				const id = wrapper.dataset.carouselId;
				if (autoplayIntervals.has(id)) {
					clearInterval(autoplayIntervals.get(id));
				}
				autoplayIntervals.set(
					id,
					setInterval(() => {
						const container = wrapper.querySelector(
							'.event-archive-carousel'
						);
						if (!container) return;
						const step = getCardStep(container);
						const maxScroll =
							container.scrollWidth - container.clientWidth;
						if (container.scrollLeft + step >= maxScroll) {
							container.scrollTo({ left: 0, behavior: 'smooth' });
						} else {
							container.scrollBy({ left: step, behavior: 'smooth' });
						}
					}, 5000)
				);
			});
		},
	},
});
