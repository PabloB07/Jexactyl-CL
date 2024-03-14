import React from 'react';
import tw from 'twin.macro';

export default () => {
    return (
        <>
            <div css={tw`md:w-1/2 h-full bg-neutral-600`}>
                <div css={tw`flex flex-col`}>
                    <h2 css={tw`py-4 px-6 font-bold`}>Ejemplos</h2>
                    <div css={tw`flex py-4 px-6 bg-neutral-500`}>
                        <div css={tw`w-1/2`}>*/5 * * * *</div>
                        <div css={tw`w-1/2`}>a cada 5 minutos</div>
                    </div>
                    <div css={tw`flex py-4 px-6`}>
                        <div css={tw`w-1/2`}>0 */1 * * *</div>
                        <div css={tw`w-1/2`}>toda hora</div>
                    </div>
                    <div css={tw`flex py-4 px-6 bg-neutral-500`}>
                        <div css={tw`w-1/2`}>0 8-12 * * *</div>
                        <div css={tw`w-1/2`}>intervalo de horas</div>
                    </div>
                    <div css={tw`flex py-4 px-6`}>
                        <div css={tw`w-1/2`}>0 0 * * *</div>
                        <div css={tw`w-1/2`}>una vez por dia</div>
                    </div>
                    <div css={tw`flex py-4 px-6 bg-neutral-500`}>
                        <div css={tw`w-1/2`}>0 0 * * MON</div>
                        <div css={tw`w-1/2`}>toda la semana</div>
                    </div>
                </div>
            </div>
            <div css={tw`md:w-1/2 h-full bg-neutral-600`}>
                <h2 css={tw`py-4 px-6 font-bold`}>Caracteres especiales</h2>
                <div css={tw`flex flex-col`}>
                    <div css={tw`flex py-4 px-6 bg-neutral-500`}>
                        <div css={tw`w-1/2`}>*</div>
                        <div css={tw`w-1/2`}>cualquier valor</div>
                    </div>
                    <div css={tw`flex py-4 px-6`}>
                        <div css={tw`w-1/2`}>,</div>
                        <div css={tw`w-1/2`}>separador de lista de valores</div>
                    </div>
                    <div css={tw`flex py-4 px-6 bg-neutral-500`}>
                        <div css={tw`w-1/2`}>-</div>
                        <div css={tw`w-1/2`}>valores de intervalo</div>
                    </div>
                    <div css={tw`flex py-4 px-6`}>
                        <div css={tw`w-1/2`}>/</div>
                        <div css={tw`w-1/2`}>valores de 2 pasos</div>
                    </div>
                </div>
            </div>
        </>
    );
};
