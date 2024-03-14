import tw from 'twin.macro';
import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { object, ref, string, setLocale } from 'yup';
import { pt } from 'yup-locales';
import { ApplicationStore } from '@/state';
import { httpErrorToHuman } from '@/api/http';
import { Formik, FormikHelpers } from 'formik';
import Field from '@/components/elements/Field';
import Input from '@/components/elements/Input';
import { RouteComponentProps } from 'react-router';
import { Actions, useStoreActions } from 'easy-peasy';
import { Button } from '@/components/elements/button/index';
import performPasswordReset from '@/api/auth/performPasswordReset';
import LoginFormContainer from '@/components/auth/LoginFormContainer';

interface Values {
    password: string;
    passwordConfirmation: string;
}

export default ({ match, location }: RouteComponentProps<{ token: string }>) => {
    const [email, setEmail] = useState('');

    const { clearFlashes, addFlash } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);

    const parsed = new URLSearchParams(location.search);
    if (email.length === 0 && parsed.get('email')) {
        setEmail(parsed.get('email') || '');
    }

    setLocale(pt);

    const submit = ({ password, passwordConfirmation }: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes();
        performPasswordReset(email, {
            token: match.params.token,
            password,
            passwordConfirmation,
        })
            .then(() => {
                // @ts-expect-error this is valid
                window.location = '/';
            })
            .catch((error) => {
                console.error(error);

                setSubmitting(false);
                addFlash({
                    type: 'danger',
                    title: 'Error',
                    message: httpErrorToHuman(error),
                });
            });
    };

    return (
        <Formik
            onSubmit={submit}
            initialValues={{
                password: '',
                passwordConfirmation: '',
            }}
            validationSchema={object().shape({
                password: string()
                    .required('Se requiere nueva contraseña.')
                    .min(8, 'Su nueva contraseña debe tener al menos 8 caracteres.'),
                passwordConfirmation: string()
                    .required('Tu nueva contraseña no coincide.')
                    // @ts-expect-error this is valid
                    .oneOf([ref('password'), null], 'Tu nueva contraseña no coincide.'),
            })}
        >
            {({ isSubmitting }) => (
                <LoginFormContainer title={'Redefinir contraseña'} css={tw`w-full flex`}>
                    <div>
                        <label>E-mail</label>
                        <Input value={email} isLight disabled />
                    </div>
                    <div css={tw`mt-6`}>
                        <Field
                            light
                            label={'Nueva Contraseña'}
                            name={'password'}
                            type={'password'}
                            description={'Las contraseñas deben tener al menos 8 caracteres.'}
                        />
                    </div>
                    <div css={tw`mt-6`}>
                        <Field light label={'Confirmar nueva contraseña'} name={'passwordConfirmation'} type={'password'} />
                    </div>
                    <div css={tw`mt-6`}>
                        <Button size={Button.Sizes.Large} css={tw`w-full`} type={'submit'} disabled={isSubmitting}>
                            Resetear contraseña
                        </Button>
                    </div>
                    <div css={tw`mt-6 text-center`}>
                        <Link
                            to={'/auth/login'}
                            css={tw`text-xs text-neutral-500 tracking-wide no-underline uppercase hover:text-neutral-600`}
                        >
                            Volver al login
                        </Link>
                    </div>
                </LoginFormContainer>
            )}
        </Formik>
    );
};
