import React from 'react';
import { useStoreState } from '@/state/hooks';
import { Button } from '@/components/elements/button';
import { linkDiscord, unlinkDiscord } from '@/api/account/discord';

export default () => {
    const discordId = useStoreState((state) => state.user.data!.discordId);

    const link = () => {
        linkDiscord().then((data) => {
            window.location.href = data;
        });
    };

    const unlink = () => {
        unlinkDiscord().then(() => {
            window.location.href = '/account';
        });
    };

    return (
        <>
            {discordId ? (
                <>
                    <p className={'text-gray-400'}>Tu cuenta está actualmente vinculada a Discord: {discordId}</p>
                    <Button.Success className={'mt-4'} onClick={() => unlink()}>
                        Desvincular cuenta de Discord
                    </Button.Success>
                </>
            ) : (
                <>
                    <p className={'text-gray-400'}>Tu cuenta no está vinculada a Discord.</p>
                    <Button.Success className={'mt-4'} onClick={() => link()}>
                        Vincular cuenta de Discord
                    </Button.Success>
                </>
            )}
        </>
    );
};
