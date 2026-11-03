export interface Auth {
    username?: string;
    password?: string;
    firstName?: string;
    lastName?: string;
    mobile?: string;
    countryCode?: string;
    refId?: string;
    email?: string;
    accept?: boolean;
}

export interface User {
    id: number;
    name: string;
    family: string;
    email: string;
    mobile: string;
    status: number;
    avatar: string;
    address: string | null;
    iranian: boolean;
    confirm_mobile_with_email?: "active" | "inactive";
    prefix_id: string;
    google_2fa_is_enabled: boolean;
}

export interface UserInfo {
    id: number;
    name: string;
    family: string;
    email: string;
    mobile: string;
    status: number;
    avatar: string;
    address: string | null;
    iranian: boolean;
}

export interface FormForgetPassword {
    step: number;
    username: string;
    type?: string;
}

export interface UserInfo {
    step: number;
    username: string;
    mobile: string;
    email: string;
    userId: string;
}

export enum StatusAuth {
    ACCEPTED = 'accepted',
    REJECTED = 'rejected',
    WAITING = 'waiting',
}
export interface DocumentVerifyData {
    url?: string;
    link?: string;
    redirect_url?: string;
}

export interface AuthenticationSettings {
    authentication_agreement: string;
    authentication_identity_image: string;
    authentication_image_guide: string;
    authentication_video_guide: string;
    first_step_manual: 'active' | 'inactive';
    limit_ip: 'active' | 'inactive';
    manual_handle_when_saturn_problem: 'active' | 'inactive';
    step_1_description: string | null;
    step_2_description: string | null;
    current_step: number;
    description: string | null;
    status: StatusAuth;
    step_2_mode: "manual" | "id_analyzer";
    id_card_image_status?: "accepted" | "rejected" | "waiting";
    agreement_video_status?: "accepted" | "rejected" | "waiting";
}
