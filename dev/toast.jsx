import VanillaToasts from "vanillatoasts";
import "./style/toast.css";

const showToast = (title, type, conf) => {
    VanillaToasts.create({
        title,
        type,
        positionClass: 'bottomCenter',
        timeout: 3500,
        ...conf
    });
}

const toast = {
    info: (title, conf = {}) => showToast(title, "info", conf),
    error: (title, conf = {}) => showToast(title, "error", conf),
    success: (title, conf = {}) => showToast(title, "success", conf),
    warning: (title, conf = {}) => showToast(title, "warning", conf),
}

export default toast;