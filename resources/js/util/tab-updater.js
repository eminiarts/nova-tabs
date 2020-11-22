export function changeActiveTab(router, tabName) {
    const {query} = router.history.current;

    router.replace({
        query: {
            ...query,
            tab: tabName,
        },
    });
}